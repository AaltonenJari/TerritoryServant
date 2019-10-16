<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Territory_controller extends CI_Controller 
{
    public function __construct() 
    {
        parent::__construct();
        
        // Load the alue model to make it available
        // to *all* of the controller's actions
        $this->load->model('Territory_model');
    }
    
    public function display($sort_by = 'alue_code', $sort_order = 'asc', $chkbox_sel = '0', $date_sel = '0', $filter = '') 
    {
        $data['display_fields'] = array(
            'alue_code'		=> 'numero',
            'alue_detail'	=> 'alue_nimi',
            'alue_location'	=> 'lisätieto',
            'lainassa'		=> 'lainassa',
            'alue_lastdate'	=> 'merkintäpvm',
            'name'	=> 'kenellä'
        );
        
        $data['database_fields'] = array(
            'alue_code'		=> 'alue_koodi',
            'alue_detail'	=> 'alue_nimi',
            'alue_location'	=> 'alue_tietoja',
            'lainassa'		=> 'alue_lainassa',
            'alue_lastdate'	=> 'alue_muutospvm',
            'event_date'	=> 'event_lastdate',
            'CONCAT(person_name, " ", person_lastname)'	=> 'nimi'
        );
        
        //Korjaa ääkköset takaisin
        $filter = urldecode($filter);
        
        $results = $this->Territory_model->search($data['database_fields'], $sort_by, $sort_order, $chkbox_sel, $date_sel);
        
        $data['alueet'] = $this->create_terr_displayrows($results,$chkbox_sel);
        
        $data['num_results'] = $results['num_rows'];
       
        $data['pagination'] = "";
        
        //Parameters back to view page
        $data['sort_by'] = $sort_by;
        $data['sort_order'] = $sort_order;
        $data['chkbox_sel'] = $chkbox_sel;
        $data['date_sel'] = $date_sel;
        $data['filter'] = $filter;
        $data['frontpage'] = 0;
        
        //State variables of territory_view
        $territory_view_state_data = array(
            'sort_by'         => $sort_by,
            'sort_order'      => $sort_order,
            'chkbox_sel'      => $chkbox_sel,
            'date_sel'        => $date_sel,
            'filter'          => $filter,
            'frontpage'       => $data['frontpage']
        );
        $this->session->set_userdata($territory_view_state_data);
        
        $this->load->view('territory_view', $data);
    }
    
    
    public function display_frontpage() 
    {
        $sort_by = 'alue_lastdate';
        $sort_order = 'asc';
        $chkbox_sel = '1';
        $date_sel = '2';
        $filter = '';
            
        $data['display_fields'] = array(
            'alue_code'		=> 'numero',
            'alue_detail'	=> 'alue_nimi',
            'alue_location'	=> 'lisätieto',
            'lainassa'		=> 'lainassa',
            'alue_lastdate'	=> 'merkintäpvm',
            'name'	=> 'kenellä'
        );
        
        $data['database_fields'] = array(
            'alue_code'		=> 'alue_koodi',
            'alue_detail'	=> 'alue_nimi',
            'alue_location'	=> 'alue_tietoja',
            'lainassa'		=> 'alue_lainassa',
            'alue_lastdate'	=> 'alue_muutospvm',
            'event_date'	=> 'event_lastdate',
            'CONCAT(person_name, " ", person_lastname)'	=> 'nimi'
        );
        
        //Korjaa ääkköset takaisin
        $filter = urldecode($filter);
        
        $results = $this->Territory_model->search_frontpage($data['database_fields'], $sort_by, $sort_order, $chkbox_sel, $date_sel);
        
        $data['alueet'] = $this->create_terr_displayrows($results,$chkbox_sel);
        
        $data['num_results'] = $results['num_rows'];
        
        $data['pagination'] = "";
        
        //Parameters back to view page
        $data['sort_by'] = $sort_by;
        $data['sort_order'] = $sort_order;
        $data['chkbox_sel'] = $chkbox_sel;
        $data['date_sel'] = $date_sel;
        $data['filter'] = $filter;
        $data['frontpage'] = 1;
        
        //State variables of territory_view
        $territory_view_state_data = array(
            'sort_by'         => $sort_by,
            'sort_order'      => $sort_order,
            'chkbox_sel'      => $chkbox_sel,
            'date_sel'        => $date_sel,
            'filter'          => $filter,
            'frontpage'       => $data['frontpage']
        );
        
        
        $this->session->set_userdata($territory_view_state_data);
        
        $this->load->view('territory_view', $data);
    }
    
    public function create_terr_displayrows($results, $chkbox_sel) 
    {
        $r = array();
        foreach ($results['rows'] as $aluerivi) {
            
            $resultrow = new stdClass;
            foreach ($aluerivi as $key=>$value) {
                switch ($key) {
                    case "alue_code":
                        $resultrow->alue_code = $value;
                        break;
                        
                    case "alue_detail":
                        $resultrow->alue_detail = $value;
                        break;
                        
                    case "alue_location":
                        $resultrow->alue_location = $value;
                        break;
                        
                    case "lainassa":
                        $resultrow->lainassa = $value;
                        break;
                        
                    case "alue_lastdate":
                        $alue_lastdate = new DateTime($value);
                        $resultrow->alue_lastdate = $alue_lastdate->format('j.n.Y');
                        break;
                        
                    case "event_date":
                        break;
                        
                    case "CONCAT(person_name, \" \", person_lastname)":
                        if ($aluerivi->lainassa == "1") {
                            $resultrow->name = $value;
                        } else {
                            $resultrow->name = "";
                        }
                        
                        break;
                        
                    default:
                        break;
                } // switch
            } // foreach aluerivi
            $r[] = $resultrow;
        }
        return $r;
    }
    
    public function update ($alue_numero) 
    {
        $columns = array(
            'alue_code',
            'alue_detail',
            'alue_location',
            'lainassa',
            'alue_lastdate',
            'CONCAT(person_name, " ", person_lastname)'
        );
        
        $alue_rivi = $this->Territory_model->get_alue_row($columns, $alue_numero);
 
        $resultrow = new stdClass;
            
        foreach ($alue_rivi as $key=>$value) {
            switch ($key) {
                case "alue_code":
                    $resultrow->alue_code = $value;
                    break;
                    
                case "alue_detail":
                    $resultrow->alue_detail = $value;
                    break;
                    
                case "alue_location":
                    $resultrow->alue_location = $value;
                    break;
                    
                case "lainassa":
                    $resultrow->lainassa = $value;
                    break;
                    
                case "alue_lastdate":
                    $resultrow->alue_lastdate = $value;
                    break;
                    
                case "CONCAT(person_name, \" \", person_lastname)":
                    $resultrow->name = $value;
                    break;
                    
                default:
                    break;
            } // switch
        } // foreach aluerivi
        
        // print_r($resultrow);
        $this->load->view('terr_mark', $resultrow);
        
    }
    
    public function update_alue()
    {
        echo "Update alue";
        
        $data = array(
            'alue_code' => $this->input->post('alue_code'),
            'lainassa_old' => $this->input->post('lainassa_old'),
            'dlainassa' => $this->session->userdata('lainassa_uusi'),
            'lastdate_old' => $this->input->post('lastdate_old'),
            'merkpvm' => $this->input->post('dmerk'),
            'jnimi_old' => $this->input->post('jnimi_old'),
            'jnimi' => $this->input->post('djnimi')
        );
        
        $this->Territory_model->update_alue($data);
        
      //  $this->load->view('Stud_view',$data);
    }
    
    public function check_territory() 
    {
        // set validation rules
        $rules = array(
            array('field' => 'djnimi',
                'label' => 'Kenellä',
                'rules' => 'callback_verify_alue')
        ) ;
        
        $this->form_validation->set_rules($rules);
        
        if ($this->form_validation->run() == false) {
            $this->update($this->input->post('alue_code'));
        } else {
            $this->update_alue();
        }
        return;
    }
    
    public function verify_alue() 
    {
        //standard behaviour is the value is only sent if the checkbox is checked.
        if (null !== $this->input->post('dlainassa')) {
            $lainassa_uusi = "1";
        } else {
            $lainassa_uusi = "0";
        }
        
        $session_data = array(
            'lainassa_uusi' => $lainassa_uusi
        );
        
        $this->session->set_userdata($session_data);
        $lainassa_vanha = $this->input->post('lainassa_old');
        $julistaja_uusi = $this->input->post('djnimi');
        
        if ($lainassa_vanha == '0' && $lainassa_uusi == 0) {
            $this->form_validation->set_message('verify_alue','Palautat palautunutta korttia. Yritä uudelleen');
            $this->session->set_flashdata('error', 'väärä lainassa-koodi');
            return false;
        } else if ($lainassa_uusi == 1 && empty($julistaja_uusi)) {
                $this->form_validation->set_message('verify_alue','Lainaajan nimi tyhjä. Yritä uudelleen');
                $this->session->set_flashdata('error', 'Lainaajan nimi tyhjä');
                return false;
        } else {
            return true;
        }
    }
    
    
    public function index()
    {
        $this->display_frontpage();
    }
    
}