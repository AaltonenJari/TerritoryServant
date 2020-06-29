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
     }
    
     public function display($code = 'A', $offset = 0) 
	 {
	    $limit = 5;
	    
	    $data['display_fields'] = array(
	        'alue_code'		=> 'numero',
	    );
	    
	    //Hae sivun tiedot
	    $page_data = $this->get_event_page($code, $offset, $limit,
	        $this->session->userdata('archive_time'),
	        $this->session->userdata('event_date_order'));
	    foreach ($page_data as $key=>$value) {
	        $data[$key] = $page_data[$key];
	    }
	    
	    $terr_count = $this->Event_model->get_alue_count($code);
	    $first = $offset + 1;
	    $last = $limit + $offset;
	    if ($last > $terr_count) {
	        $last = $terr_count;
	    }
	    $code_sel = $code . $first . "-" . $last;
	    $data['code_sel'] = $code_sel;
	    
	    //Number of cards
	    $data['num_headers'] = $terr_count;
	    
	    $data['sel_data'] = $this->get_terr_group_selector_data();
	   
	    //Muodostetaan sivutusvalikot sivun loppuun
        $data = $this->set_pagination_rows($data, $code, $limit);
        
        
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
        
        //Hidas haku; haetaan nyt vain kaksi riviä
//         $page_data = $this->get_event_page($code, 0, $limit,
//             $this->session->userdata('archive_time'),
//             "ASC");
//         $event_data[] = $page_data;
//         $page_data = $this->get_event_page($code, 5, $limit,
//             $this->session->userdata('archive_time'),
//             "ASC");
//         $event_data[] = $page_data;
        
        foreach ($terr_groups as $code=>$count) {
            for ($offset = 0; $offset < $count; $offset = $offset + $limit) {
                $page_data = $this->get_event_page($code, $offset, $limit,
                               $this->session->userdata('archive_time'),
                               "ASC");
                $event_data[] = $page_data;
            }
        }

        $data['bookkeeping'] = $event_data;
        
        $this->load->view('bookkeeping_view', $data);
    }
    
    function get_event_page($code = 'A', $offset = 0, $limit = 5, $archive_time, $event_date_order)
    {
        $page_data = array();
        
        $event_hdr_fields = array(
            'alue_code'		=> 'alue_koodi',
            'alue_id'	    => 'alue_id',
        );
        
        $results = $this->Event_model->search_headers($event_hdr_fields, $code, $limit, $offset);
        
        $event_fields = array(
            'alue_code'		=> 'alue_koodi',
            'event_type'	=> 'event_tyyppi',
            'event_date'	=> 'event_date',
            'person_name'	=> 'etunimi',
            'person_lastname'	=> 'sukunimi'
        );
        
        $event_hdrs = array();
        $events_data = array();
        
        foreach ($results['rows'] as $aluerivi)
        {
            $resultrow = new stdClass;
            foreach ($aluerivi as $key=>$value) {
                switch ($key) {
                    case "alue_code":
                        $resultrow->alue_code = $value;
                        break;
                        
                    case "alue_id":
                        //Hae alueen tapahtumat tunnuksella
                        $event_results = $this->Event_model->search_event_data($event_fields, $value, 
                                           $archive_time, 
                                           $event_date_order);
                        $events_alue = $this->tabulate_alue_events($event_results, $event_date_order);
                        $events_data[] = $events_alue;
                        break;
                        
                    default:
                        break;
                } // switch
            } // foreach aluerivi
            $event_hdrs[] = $resultrow;
        }
        
        $page_data['event_headers'] = $event_hdrs;
        $page_data['event_data'] = $this->tabulate($events_data);
        $page_data['page_cards'] = $results['num_rows'];
        
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
        $terrgroup = array();
        $terrgroup_selection = array();
        
        //Hae aluekoodit
        $tresults = $this->Event_model->get_terr_codes();
        
        //Tee taulukko, jossa on avaimena aluekoodi ja arvona korttien lkm
        foreach ($tresults['rows'] as $territory_codes) {
            foreach ($territory_codes as $key=>$value) {
                $terr_count = $this->Event_model->get_alue_count($value);
                
                for ($offset = 0; $offset < $terr_count; $offset = $offset + $limit) {
                    //Lisätään avain ja arvo taulukkoon
                    $terrgroup['code'] = $value;
                    $terrgroup['offset'] = $offset+1;
                    $last = $offset + $limit;
                    if ($last > $terr_count) {
                        $last = $terr_count;
                    }
                    $terrgroup['last'] = $last;
                    $terrgroup_selection[] = $terrgroup;
                    
                }
             }
        }
        return $terrgroup_selection;
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
    
    function tabulate_alue_events($event_results, $event_date_order) 
    {
        $e = array();
        $event_result_row = new stdClass;
         
        $prev_event_type = "0";
        
        foreach ($event_results['rows'] as $event_row)
        {
            foreach ($event_row as $key=>$value) 
            {
                switch ($key) {
                    case "alue_code":
                        if ($event_row->event_type == $prev_event_type) {
                            //Käsitellään tilanne, jossa tapahtuma puuttuu
                            if ($event_row->event_type == "1") {
                                if ($event_date_order == "ASC") {
                                    $e[] = $event_result_row;
                                    $event_result_row = new stdClass;
                                }
                            }
                            if ($event_row->event_type == "2") {
                                if ($event_date_order == "DESC") {
                                    $e[] = $event_result_row;
                                    $event_result_row = new stdClass;
                                }
                            }
                        }
                        $event_result_row->code = $value;
                        break;
                        
                    case "event_type":
                        break;
                    
                    case "event_date":
                        $event_date = new DateTime($value);
                        if ($event_row->event_type == "2") {
                            $event_result_row->returned = $event_date->format('j.n.Y');
                        } else if ($event_row->event_type == "1") {
                            $event_result_row->taken = $event_date->format('j.n.Y');
                        }
                        break;
                        
                    case "person_name":
                        break;
                        
                    case "person_lastname":
                        if ($this->session->userdata('name_presentation') == "0") {
                            //0 = firstname lsatname, 1 = lastmame, firstname; (default)
                            $name_delim = ' ';
                            $event_result_row->name = $event_row->person_name . $name_delim . $value;
                        } else {
                            $name_delim = ', ';
                            $event_result_row->name =  $value . $name_delim . $event_row->person_name;
                        }
                        
                        if ($event_date_order == "DESC") {
                            if ($event_row->event_type == "1") {
                                $e[] = $event_result_row;
                                $event_result_row = new stdClass;
                            }
                        }
                        if ($event_date_order == "ASC") {
                            if ($event_row->event_type == "2") {
                                $e[] = $event_result_row;
                                $event_result_row = new stdClass;
                            }
                        }
                        $prev_event_type = $event_row->event_type;
                        break;
                        
                    default:
                        break;
                } // switch
            } // foreach aluerivi
        }
        
        //Tarkistetaan viimeinen rivi. Jos se pn 'pariton', lisätään se mukaan
        if (count($event_results['rows']) > 0) {
            $last_row = end ($event_results['rows']);
            foreach ($last_row as $key=>$value) {
                switch ($key) {
                    case "alue_code":
                        $event_result_row->code = $value;
                        break;
                        
                    case "event_date":
                        $event_date = new DateTime($value);
                        if ($event_row->event_type == "2") {
                            $event_result_row->returned = $event_date->format('j.n.Y');
                        } else if ($event_row->event_type == "1") {
                            $event_result_row->taken = $event_date->format('j.n.Y');
                        }
                        break;
                        
                    case "person_name":
                        break;
                        
                    case "person_lastname":
                        if ($this->session->userdata('name_presentation') == "0") {
                            $name_delim = ' ';
                            $event_result_row->name = $event_row->person_name . $name_delim . $value;
                        } else {
                            $name_delim = ', ';
                            $event_result_row->name =  $value . $name_delim . $event_row->person_name;
                        }
                        
                        if ($event_date_order == "DESC") {
                            if ($event_row->event_type == "2") {
                                $e[] = $event_result_row;
                            }
                        }
                        if ($event_date_order == "ASC") {
                            if ($event_row->event_type == "1") {
                                $e[] = $event_result_row;
                            }
                        }
                        break;
                    default:
                        break;
                } // switch
            } // foreach last_row
        }
        
        return $e;     
    }
    
    function tabulate($events_data) 
    {
        $tab_result_rows = array();
        
        $terr_count=count($events_data);
        
        //Haetaan alue, jolla on eniten tapahtumia, ja poimitaan tämä määrä
        $maxEventCount = 0;
        
        foreach ($events_data as $alue) {
            $eventcount=count($alue);
            if ($eventcount > $maxEventCount) {
                $maxEventCount = $eventcount;
            }
        }
        
        //Käännetään haetut tiedot siten, että rivit muunnetaan sarakkeiksi
        for($idx=0;$idx<$maxEventCount;$idx++) 
        {
            $tabrow_names = array();
            $tabrow_events = array();
            $tabrow = array();

            for($ai=0;$ai<$terr_count;$ai++) {
                //Asetetaan avain
                $names_key = 'name_' . $ai;
                $taken_key = 'taken_' . $ai;
                $returned_key = 'returned_' . $ai;
                
                //Jos aluetta vastaava tieto löytyy, asetetaan se arvoksi, muuten käytetään tyhjää
                if ($idx < count($events_data[$ai])) {
                    $names_value = $events_data[$ai][$idx]->name;
                    
                    if (property_exists($taken_value = $events_data[$ai][$idx], 'taken')) {
                        $taken_value = $events_data[$ai][$idx]->taken;
                    } else {
                        $taken_value = "";
                    }
                    
                    if (property_exists($events_data[$ai][$idx], 'returned')) {
                        $returned_value = $events_data[$ai][$idx]->returned;
                    } else {
                        $returned_value = "";
                    }
                } else {
                    $names_value = "";
                    $taken_value = "";
                    $returned_value = "";
                }
                
                //Lisätään avain ja arvo taulukkoon
                $tabrow_names[$names_key] = $names_value;
                $tabrow_events[$taken_key] = $taken_value;
                $tabrow_events[$returned_key] = $returned_value;
            }
            //Lisätään rivikohtaiset tiedot taulukoksi
            $tabrow_names_key = 'names';
            $tabrow[$tabrow_names_key] = $tabrow_names;
            
            $tabrow_events_key = 'dates';
            $tabrow[$tabrow_events_key] = $tabrow_events;
            
            //Lisätään rivin tiedot tulostaulukkoon
            $tab_result_rows[] = $tabrow;
        }
         
        return (object) $tab_result_rows;
    }
}