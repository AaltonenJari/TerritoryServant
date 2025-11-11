<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Event_controller extends CI_Controller 
{
    public function __construct() 
    {
        parent::__construct();
        
        // Load the pagination 
        $this->load->library('pagination');

        // Load the model 
        $this->load->model('Event_model');
    
        // Load the undo/redo class
        $this->load->model('UndoRedoStack');
    }
    
     public function display($code = 'A', $offset = 0) 
	 {
	    $limit = 5;
	    
	    $data['display_fields'] = array(
	        'alue_code'		=> 'numero',
	    );
	    
	    //State variables for event_view
	    $event_view_state_data = array(
	        'page_code'       => $code,
	        'page_offset'     => $offset
	    );
	    $this->session->set_userdata($event_view_state_data);
	    
	    //Poistetaan virheteksti näkyvistä
	    if(isset($_SESSION['error'])){
	        unset($_SESSION['error']);
	    }
	    
	    //Hae sivun tiedot
	    $page_data = $this->get_event_page($code, $offset, $limit,
	        $this->session->userdata('archiveYears'),
	        $this->session->userdata('eventOrder'));

	    foreach ($page_data as $key=>$value) {
	        $data[$key] = $page_data[$key];
	    }
	    $terr_count = $this->Event_model->get_alue_count($code);
	    
	    //Hae sivun otsaikkorivi alasvetovalikkoa varten
	    $page_hdr = $this->get_event_page_headers($code, $offset, $limit);
	    //Array to string conversion
	    $code_sel = $page_hdr['code'] . $page_hdr['first'] . "-" . $page_hdr['last'];
	    $data['code_sel'] = $code_sel;
	    $data['offset'] = $offset;
	    
	    
	    //Number of cards
	    $data['num_headers'] = $terr_count;
	    
	    $data['sel_data'] = $this->get_terr_group_selector_data();
	   
	    //Muodostetaan sivutusvalikot sivun loppuun
        $data = $this->set_pagination_rows($data, $code, $limit);
        
        //Alusta tietorakenne undo/redo - toimintoa varten
        $undo_redo_stack = new UndoRedoStack();
        $_SESSION['undo_redo_stack'] = serialize($undo_redo_stack);
        
        $data['saved_height'] = $this->session->userdata('table_height');
        $this->load->view('event_view', $data);
    }
    
    public function display_bookkeeping()
    {
        $code = 'A';
        $limit = 5;
        
        $data['display_fields'] = array(
            'alue_code'		=> 'numero',
        );
        
        //Hae alueryhmät ja ryhmien korttien lukumäärät
        $terr_groups = $this->get_terr_group_data();
        
        //Hae koko kirjanpito
        $event_data = array();
        
        foreach ($terr_groups as $code=>$count) {
            for ($offset = 0; $offset < $count; $offset = $offset + $limit) {
                $page_data = $this->get_event_page($code, $offset, $limit,
                               $this->session->userdata('archiveYears'),
                               $this->session->userdata('eventOrder'));
                $event_data[] = $page_data;
            }
        }

        $data['bookkeeping'] = $event_data;
        
        $data['saved_height'] = $this->session->userdata('table_height');
        $this->load->view('bookkeeping_view', $data);
    }
    
    function get_event_page($code = 'A', $offset = 0, $limit = 5, $archive_time, $event_date_order)
    {
        $page_data = array();
        
        $event_hdr_fields = array(
            'alue_code'   => 'alue_koodi',
            'alue_id'     => 'alue_id',
            'alue_group'  => 'ryhma'
        );
        
        $results = $this->Event_model->search_headers($event_hdr_fields, $code, $limit, $offset);
        
        $event_fields = array(
            'alue_code'        => 'alue_koodi',
            'event_type'       => 'event_tyyppi',
            'event_date'       => 'event_date',
            'person_name'      => 'etunimi',
            'person_lastname'  => 'sukunimi'
        );
        
        $event_hdrs = [];
        $events_data = [];
        
        foreach ($results['rows'] as $alue) {
            $resultrow = new stdClass;
            $resultrow->alue_code  = $alue->alue_code;
            $resultrow->alue_id    = $alue->alue_id;
            $resultrow->alue_group = $alue->alue_group;
            
            if ($alue->alue_group != 99) {
                // Hae alueen tapahtumat tunnuksella
                $event_results = $this->Event_model->search_event_data(
                    $event_fields,
                    $alue->alue_id,
                    $archive_time,
                    $event_date_order
                    );
                
                $events_alue = $this->Event_model->tabulate_alue_events(
                    $event_results,
                    $event_date_order
                    );
                
                $events_data[] = $events_alue;
            } else {
                // Luo "poistettu alue" -objekti
                $deleted = new stdClass;
                $deleted->code     = $alue->alue_code;
                $deleted->returned = '';
                $deleted->name     = 'Alue poistettu';
                $deleted->taken    = '';
                
                // Asetetaan se taulukkomuotoon yhdenmukaisesti muiden kanssa
                $events_data[] = [$deleted];
            }
            
            $event_hdrs[] = $resultrow;
        }
        
        $page_data['event_headers'] = $event_hdrs;
        $page_data['event_data']    = $this->Event_model->tabulate($events_data);
        $page_data['page_cards']    = $results['num_rows'];
        
        return $page_data;
    }
    
    function get_terr_group_data()
    {
        $terrgroups = array();
        
        //Hae aluekoodit
        $tresults = $this->Event_model->get_terr_codes();
        
        //Tee taulukko, jossa on avaimena aluekoodi ja arvona korttien lkm
        foreach ($tresults['rows'] as $territory_codes) {
            foreach ($territory_codes as $key=>$value) {
                $terr_count = $this->Event_model->get_alue_count($value);
                
                //Lisätään avain ja arvo taulukkoon
                $terrgroups[$value] = $terr_count;
            }
        }
        return $terrgroups;
    }
    
    function get_terr_group_selector_data()
    {
        $limit = 5;
        $last = 5;
        $terrgroup_selection = array();
        
        //Hae aluekoodit
        $tresults = $this->Event_model->get_terr_codes();
        
        //Tee taulukko, jossa on avaimena aluekoodi ja sivun ensimmäinen ja viimeinen alue
        foreach ($tresults['rows'] as $territory_codes) {
            foreach ($territory_codes as $key=>$value) {
                $terr_count = $this->Event_model->get_alue_count($value);
                
                for ($offset = 0; $offset < $terr_count; $offset = $offset + $limit) {
                    //Haetaan arvot kannasta
                    $page_hdrs = $this->get_event_page_headers($value, $offset, $limit);
                    
                    //Lisätään haettu alkio taulukkoon
                    $terrgroup_selection[] = $page_hdrs;
                    
                }
             }
        }
        return $terrgroup_selection;
    }
    
    function get_event_page_headers($code = 'A', $offset = 0, $limit = 5) 
    {
        $page_hdr_fields = array(
            'alue_code'		=> 'alue_koodi'
        );
        
        $results = $this->Event_model->search_headers($page_hdr_fields, $code, $limit, $offset);
        $page_hdrs = array();
        
        $last_column = $results['num_rows'] - 1;
        
        $page_hdrs['code'] = $code;
        $page_hdrs['offset'] = $offset;
        $page_hdrs['first'] =substr($results['rows'][0]->alue_code, 1);
        $page_hdrs['last'] = substr($results['rows'][$last_column]->alue_code, 1);
       
        return $page_hdrs;
    }
    
    function get_terr_selectors($code) 
    {
        $limit = 5;
        $last = 5;
        $selectors = array();
        
        $terr_count = $this->Event_model->get_alue_count($value);
        
        for ($offset = 0; $offset < $terr_count; $offset = $offset + $limit) {
            $first = $offset + 1;
            $last = $limit + $offset;
            if ($last > $terr_count) {
                $last = $terr_count;
            }
            $code_sel = $code . $first . "-" . $last;
            $selectors[] = $terrgroup;
        }
        return $selectors;
    }
    
    function set_pagination_rows($page_data, $code, $limit) 
    {
        //Get territory codes
        $tresults = $this->Event_model->get_terr_codes();
        
        //print_r($page_data['sel_data']);
        $t="<div class='center'>";
        $t="<div class='territorycodes'>";
        
        foreach ($tresults['rows'] as $aluekoodi)
        {
            foreach ($aluekoodi as $key=>$value) {
                if ($value == $code) {
                    $t = $t . "<a class='active' href='".site_url('event_controller/display/'.$value)."'>".$value."</a>";
                } else {
                    $t = $t . "<a href='".site_url('event_controller/display/'.$value)."'>".$value."</a>";
                }
                
            } // foreach aluekoodi
        }
        $t = $t . "</div>";
        //$t = $t . "</div>";
        
        $page_data['terr_codes'] = $t;
        
        //Pagination configuration
        $config = array();
        $config['base_url'] = site_url('event_controller/display/'.$code);
        $config['total_rows'] = $page_data['num_headers'];
        $config['per_page'] = $limit;
        $config['uri_segment'] = 4;
        
        $config['full_tag_open'] = '<div class="pagination">';
        $config['full_tag_close'] = '</div>';
        
        $config['first_link'] = '&laquo;';
        $config['first_tag_open'] = '<span class="firstlink">';
        $config['first_tag_close'] = '</span>';
        
        $config['last_link'] = '&raquo;';
        $config['last_tag_open'] = '<span class="lastlink">';
        $config['last_tag_close'] = '</span>';
        
        $config['next_link'] = '&gt';
        $config['next_tag_open'] = '<span class="nextlink">';
        $config['next_tag_close'] = '</span>';
        
        $config['prev_link'] = '&lt';
        $config['prev_tag_open'] = '<span class="prevlink">';
        $config['prev_tag_close'] = '</span>';
        
        $config['cur_tag_open'] = '<span class="curlink">';
        $config['cur_tag_close'] = '</span>';
        
        $config['num_tag_open'] = '<span class="numlink">';
        $config['num_tag_close'] = '</span>';
        
        
        //Initialize pagination library
        $this->pagination->initialize($config);
        
        //Pagination links
        $page_data["links"] = $this->pagination->create_links();
        
        return $page_data;
    }
    
    public function event_delete_view($selectedYears = '3', $deletePersons = FALSE)
    {
        $data = $this->getCleanupSummaryData($selectedYears, $deletePersons);
        
        $this->load->view('event_delete_dialog', $data);
    }
    
    public function check_delete_options()
    {
        $selectedYears = $this->input->post('archive_years');
        $deletePersons = $this->input->post('delete_persons'); // palauttaa '1' jos valittu, NULL jos ei
        
        $action = $this->input->post('action');
        switch ($action) {
            case "Poista":
                if(isset($_SESSION['error'])){
                    unset($_SESSION['error']);
                }
                
                $deleted_event_count = $this->delete_events($selectedYears);
                if ($deleted_event_count === null) {
                    echo "virhe";
                    $data['error_title'] = 'Virhe tietojen poistossa';
                    $data['error_message'] = 'Valitse alla olevasta painikkeesta palataksesi pääsivulle.';
                    $data['base_url'] = 'event_controller/display';
                    $this->load->view('common/territory_error_view', $data);
                    return;
                }
                
                $deleted_person_count = 0;
                if ($deletePersons) {
                    // Checkbox oli valittuna
                    $deleted_person_count = $this->delete_persons_with_no_events();
                }
                
                // 🔹 Kootaan data näkymälle
                $data = $this->getCleanupSummaryData($selectedYears, $deletePersons, $deleted_event_count, $deleted_person_count);
                
                // Näytetään näkymä uudelleen palautteella
                $this->load->view('event_delete_dialog', $data);
                
                break;
                
            case "Paluu":
                if(isset($_SESSION['error'])){
                    unset($_SESSION['error']);
                }
                //Palataan päänäytölle
                $this->display();
                break;
                
            default:
                $msg = "Tunnistamaton toiminto";
                $this->session->set_flashdata('error', $msg);
                $this->event_delete_view($selectedYears, $deletePersons);
                break;
        } // switch
        
        return;
    }
    
    private function getCleanupSummaryData($selectedYears, $deletePersons = false, $deleted_event_count = 0, $deleted_person_count = 0)
    {
        return [
            'archiveYearsOptions' => [
                '2'  => '2 vuotta',
                '3'  => '3 vuotta',
                '4'  => '4 vuotta',
                '5'  => '5 vuotta',
                '6'  => '6 vuotta',
                '7'  => '7 vuotta',
                '8'  => '8 vuotta',
                '9'  => '9 vuotta',
                '10' => '10 vuotta'
            ],
            'selectedYear'         => $selectedYears,
            'deletePersons'        => $deletePersons,
            'deleted_event_count'  => $deleted_event_count,
            'deleted_person_count' => $deleted_person_count
        ];
    }
    
    public function delete_events($selectedYears) {
        // Hae nykyinen vuosi ja kuukausis
        $currentYear = date('Y');
        $currentMonth = date('n');
        
        // Jos ollaan syyskuusta eteenpäin, otetaan yksi vuosi enemmän mukaan
        if ($currentMonth >= 9) {
            $year = $currentYear - $selectedYears + 1;
        } else {
            $year = $currentYear - $selectedYears;
        }
        
        $limit_date = (new DateTime("$year-09-01"))->format('Y-m-d'); // Pvm muodossa "YYYY-MM-DD"
        
        $deleted_rows = $this->Event_model->delete_events($limit_date);
        
        if ($deleted_rows === null) {
            return;
        }
        
        return $deleted_rows;
    }
    
    public function delete_persons_with_no_events() {
        $deleted_rows = $this->Event_model->delete_persons_having_no_events();
        
        if ($deleted_rows === null) {
            return;
        }
        
        return $deleted_rows;
    }
    
    public function index()
    {
        $this->display();
    }
    
 
}