<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Maintenance_controller extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        
        // Load the maintenance model to make it available
        // to *all* of the controller's actions
        $this->load->model('Territory_model');
        $this->load->model('Event_model');
        $this->load->model('Maintenance_model');
        $this->load->model('UndoRedoStack');
    }
    
    public function display($sort_by = 'alue_code', $sort_order = 'asc', $code_sel = '0', $filter = '') 
    {
        //State variables for maintenance_view
        if (($sort_by != $this->session->userdata('sort_by')) ||
            ($sort_order != $this->session->userdata('sort_order')) ||
            ($code_sel != $this->session->userdata('code_sel')) ||
            ($filter != $this->session->userdata('filter'))) {
                
                //Jos jokin tilamuuttuja muuttuu, poistetaan virheteksti näkyvistä
                if(isset($_SESSION['error'])){
                    unset($_SESSION['error']);
                }
            }
  
        //Tallenna näytön uusi tila
        $territory_view_state_data = array(
            'sort_by'         => $sort_by,
            'sort_order'      => $sort_order,
            'code_sel'        => $code_sel,
            'filter'          => $filter
        );
        $this->session->set_userdata($territory_view_state_data);
        
        //Hakuparametrit näytölle
        $data['display_fields'] = array(
            'alue_code'		=> 'numero',
            'alue_detail'	=> 'alue_nimi',
            'alue_location'	=> 'lisätieto',
            'alue_taloudet'	=> 'koko',
            'event_count'	=> 'poisto'
        );
        
        //Hakuparametrit kantaan
        $data['database_fields'] = array(
            'alue_code'		=> 'numero',
            'alue_detail'	=> 'alue_nimi',
            'alue_location'	=> 'lisätieto',
            'alue_taloudet'	=> 'koko',
            'event_count'	=> 'määrä'
        );

        //Korjaa ääkköset takaisin
        $filter = urldecode($filter);
        
        //Hae tiedot
        $results = $this->Maintenance_model->search($data['database_fields'], $sort_by, $sort_order, $code_sel);
        //Tiedot näytölle sopiviksi
        $data['alueet'] = $this->create_displayrows($results);
        
        $data['num_results'] = $results['num_rows'];
        
        //Parameters back to view page
        $data['sort_by'] = $sort_by;
        $data['sort_order'] = $sort_order;
        $data['code_sel'] = $code_sel;
        $data['filter'] = $filter;
        
        //Hae aluekoodit
        $tresults = $this->Event_model->get_terr_codes();
        $data['territory_codes'] = $tresults['rows'];

        //Alusta tietorakenne undo/redo - toimintoa varten
        //Jos parametreja ei ole annettu, alusta tietorakenne
        $numargs = func_num_args();
        if ($numargs == 0) {
            /** Initialize **/
            $undo_redo_stack = new UndoRedoStack();
            
            //Poistetaan virheteksti näkyvistä
            if(isset($_SESSION['error'])){
                unset($_SESSION['error']);
            }
        } else {
            //Muuten
            if (!isset($_SESSION['undo_redo_terr_edit'])) {
                /** Initialize **/
                $undo_redo_stack = new UndoRedoStack();
            } else {
                /** UNSERIALIZE **/
                $undo_redo_stack = unserialize($_SESSION['undo_redo_terr_edit']);
            }
        }
        
        $data['can_undo'] = $undo_redo_stack->can_undo();
        $data['can_redo'] = $undo_redo_stack->can_redo();
        
        /** SERIALIZE **/
        $_SESSION['undo_redo_terr_edit'] = serialize($undo_redo_stack);
        
        $this->load->view('maintenance_view', $data);
    }
    
    private function create_displayrows($results)
    {
        $r = array();
        foreach ($results['rows'] as $fetched_row) {
            
            $resultrow = new stdClass;
            foreach ($fetched_row as $key=>$value) {
                switch ($key) {
                    case "alue_code":
                    case "alue_detail":
                    case "alue_location":
                    case "alue_taloudet":
                    case "event_count":
                        $resultrow->$key = $value;
                        break;
                        
                    default:
                        break;
                } // switch
            } // foreach results row
            $r[] = $resultrow;
        }
        return $r;
    }
    
    public function update_rows()
    {
        $field_alue_codes = $this->input->post('alue_code_old');
        
        $field_alue_details = $this->input->post('alue_detail');
        $field_old_alue_details = $this->input->post('alue_detail_old');
        
        $field_alue_locations = $this->input->post('alue_location');
        $field_old_alue_locations = $this->input->post('alue_location_old');
        
        $field_alue_taloudet = $this->input->post('alue_taloudet');
        $field_old_alue_taloudet = $this->input->post('alue_taloudet_old');
        
        $r = array();
        
        /** UNSERIALIZE **/
        $undo_redo_stack = unserialize($_SESSION['undo_redo_terr_edit']);
        
        for ($i = 0; $i < sizeof($field_alue_codes); $i++) {
            if (($field_alue_details[$i] != $field_old_alue_details[$i]) ||
                ($field_alue_locations[$i] != $field_old_alue_locations[$i]) ||
                ($field_alue_taloudet[$i] != $field_old_alue_taloudet[$i])) {
                    $update_data = array(
                        'alue_detail'	=> $field_alue_details[$i],
                        'alue_location'	=> $field_alue_locations[$i],
                        'alue_taloudet'	=> $field_alue_taloudet[$i]
                    );
                    $this->Territory_model->update($update_data, $field_alue_codes[$i]);
                    
                    //Tiedot undo/redo -toimintoa varten
                    $terr_data_old = array(
                        'alue_detail'	=> $field_old_alue_details[$i],
                        'alue_location'	=> $field_old_alue_locations[$i],
                        'alue_taloudet'	=> $field_old_alue_taloudet[$i],
                        'alue_lastdate'	=> '',
                        'alue_group'	=> '',
                        'lainassa'  	=> ''
                    );
                    
                    $terr_data_new = array(
                        'alue_detail'	=> $field_alue_details[$i],
                        'alue_location'	=> $field_alue_locations[$i],
                        'alue_taloudet'	=> $field_alue_taloudet[$i],
                        'alue_lastdate'	=> '',
                        'alue_group'	=> '',
                        'lainassa'  	=> ''
                    );
                    
                    $terr_edit_info = array(
                        'operation'	=> 'edit',
                        'key'		=> $field_alue_codes[$i],
                        'data_old'	=> $terr_data_old,
                        'data_new'	=> $terr_data_new
                    );
                    //Päivityksen tiedot muistiin
                    $undo_redo_stack->execute($terr_edit_info);
                    $_SESSION['undo_redo_terr_edit'] = serialize($undo_redo_stack);
                }
        }
        
        /** SERIALIZE **/
        $_SESSION['undo_redo_terr_edit'] = serialize($undo_redo_stack);
        
        //Palataan päänäytölle siinä tilassa, kuin se oli ennen päivitystä
        $this->display($this->session->userdata('sort_by'),
            $this->session->userdata('sort_order'),
            $this->session->userdata('code_sel'),
            $this->session->userdata('filter'));
        
        return ;
    }
    
    public function insert($terr_code_group = 'A')
    {
        //Jos aluekoodivalitsin hakee kaikki alueet, haetaan ryhmä 'A'
        if ($terr_code_group == '0') {
            $terr_code_group = 'A';
        }
        
        $first_vacant = $this->Maintenance_model->get_first_vacant_number($terr_code_group);
        $data = array(
            'alue_code' => $first_vacant,
            'alue_detail' => '',
            'alue_location' => '',
            'alue_taloudet' => '30'
        );
        
        $this->load->view('terr_insert', $data);
    }
    
    public function check_update($terr_code_group = 'A', $filter = '')
    {
        //Jos aluekoodivalitsin hakee kaikki alueet, haetaan ryhmä 'A'
        if ($terr_code_group == '0') {
            $terr_code_group = 'A';
        }
                
        //State variables of person_view
        $territory_view_state_data = array(
            'filter'          => $filter
        );
        $this->session->set_userdata($territory_view_state_data);
        
        $action = $this->input->post('action');
        switch ($action) {
            case "Päivitä":
            case "Update":
                $this->update_rows();
                break;
                
            case "Lisää":
            case "Add":
                $this->insert($terr_code_group);
                break;
                
            case "Undo":
                $this->undo();
                break;

            case "Redo":
                $this->redo();
                break;
                
            default:
                $msg = "Tunnistamaton toiminto.";
                $num_vars = count( explode( '###', http_build_query($_POST, '', '###') ) );
                $max_num_vars = ini_get('max_input_vars');
                if ($num_vars > $max_num_vars) {
                    $msg .= " Input-parametreja on enemmän kuin " . $max_num_vars;
                }
                $this->session->set_flashdata('error', $msg);
                
                //Palataan päänäytölle siinä tilassa, kuin se oli ennen päivitystä
                $this->display($this->session->userdata('sort_by'),
                    $this->session->userdata('sort_order'),
                    $this->session->userdata('code_sel'),
                    $this->session->userdata('filter'));
                break;
        } // switch
        
       return;
    }

    public function check_insert()
    {
        $action = $this->input->post('action');
        switch ($action) {
            case "Lisää":
                // set validation rules
                $rules = array(
                array('field' => 'djnimi',
                  'label' => 'Kenellä',
                  'rules' => 'callback_verify_update')
                ) ;
                // check input data
                $this->form_validation->set_rules($rules);
                
                if ($this->form_validation->run() == false) {
                    $this->insert($this->session->userdata('code_sel'));
                } else {
                    $this->add();
                }
                break;
                
            case "Paluu":
                if(isset($_SESSION['error'])){
                    unset($_SESSION['error']);
                }
                //Palataan päänäytölle siinä tilassa, kuin se oli ennen päivitystä
                $this->display($this->session->userdata('sort_by'),
                    $this->session->userdata('sort_order'),
                    $this->session->userdata('code_sel'),
                    $this->session->userdata('filter'));
                break;
                
            default:
                $msg = "Tunnistamaton toiminto.";
                $num_vars = count( explode( '###', http_build_query($_POST, '', '###') ) );
                $max_num_vars = ini_get('max_input_vars');
                if ($num_vars > $max_num_vars) {
                    $msg .= " Input-parametreja on enemmän kuin " . $max_num_vars;
                }
                $this->session->set_flashdata('error', $msg);
                
                $this->insert($this->session->userdata('code_sel'));
                break;
        } // switch
        
        return;
    }
    public function verify_update() 
    {
        if (empty($this->input->post('alue_code'))) {
            $this->session->set_flashdata('error', 'Alueen numero ei saa olla tyhjä.');
            return false;
        }
        if (empty($this->input->post('alue_detail')) && empty($this->input->post('alue_location'))) {
            $this->session->set_flashdata('error', 'Alueen nimi ja lisätieto ovat kumpikin tyhjiä.');
            return false;
        }
        //Tarkista, onko alue jo kannassa
        $found = $this->Maintenance_model->row_exists($this->input->post('alue_code'));
        if ($found > 0)  {
            $msg = "Ei voi lisätä. Alue " . $this->input->post('alue_code') . " on jo kannassa.";
            $this->session->set_flashdata('error', $msg);
            return false;
        }
            
        //Poistetaan aikaisemmin näkynyt virheteksti
        if(isset($_SESSION['error'])){
            unset($_SESSION['error']);
        }
        return true;
    }
    
    public function add()
    {
        /** UNSERIALIZE **/
        $undo_redo_stack = unserialize($_SESSION['undo_redo_terr_edit']);
        
        //Uuden alueen oletuskäyntipäivä 5 vuotta sitten
        $endDate = new DateTime('first day of january');
        $endDate->modify('-5 year');
        
        $insert_data = array(
            'alue_code' => $this->input->post('alue_code'),
            'alue_detail' => $this->input->post('alue_detail'),
            'alue_location' => $this->input->post('alue_location'),
            'alue_taloudet' => $this->input->post('alue_taloudet'),
            'alue_lastdate'	=> $endDate->format('Y-m-d'),
            'alue_group'	=> '5',
            'lainassa'  	=> '0'
        );
        
        //Tiedot undo/redo -toimintoa varten
        $terr_data_new = array(
            'alue_detail'	=> $this->input->post('alue_detail'),
            'alue_location'	=> $this->input->post('alue_location'),
            'alue_taloudet'	=> $this->input->post('alue_taloudet'),
            'alue_lastdate'	=> $endDate->format('Y-m-d'),
            'alue_group'	=> '5',
            'lainassa'  	=> '0'
        );
        
        $terr_edit_info = array(
            'operation'	=> 'add',
            'key'		=> $this->input->post('alue_code'),
            'data_old'	=> null,
            'data_new'	=> $terr_data_new
        );
        
        //Lisäyksen tiedot muistiin
        $undo_redo_stack->execute($terr_edit_info);
        
        //Lisää uusi
        $this->Maintenance_model->insert($insert_data);
        
        /** SERIALIZE **/
        $_SESSION['undo_redo_terr_edit'] = serialize($undo_redo_stack);
        
        $this->display($this->session->userdata('sort_by'),
            $this->session->userdata('sort_order'),
            $this->session->userdata('code_sel'),
            $this->session->userdata('filter'));

    }
        
    public function delete($terr_nbr, $filter = '')
    {
        //State variables of territory_view
        $territory_view_state_data = array(
            'filter'          => $filter
        );
        $this->session->set_userdata($territory_view_state_data);
        
        /** UNSERIALIZE **/
        $undo_redo_stack = unserialize($_SESSION['undo_redo_terr_edit']);
        
        //Haetaan poistettavan alueen tiedot
        $columns = array(
            'alue_code',
            'alue_detail',
            'alue_location',
            'alue_taloudet',
            'alue_lastdate',
            'alue_group',
            'lainassa'
        );
        $resultrow = $this->Maintenance_model->get_row_by_key($columns, $terr_nbr);
        
        
        //Tiedot undo/redo -toimintoa varten
        $terr_data_old = array(
            'alue_detail'	=> $resultrow['alue_detail'],
            'alue_location'	=> $resultrow['alue_location'],
            'alue_taloudet'	=> $resultrow['alue_taloudet'],
            'alue_lastdate'	=> $resultrow['alue_lastdate'],
            'alue_group'	=> $resultrow['alue_group'],
            'lainassa'  	=> $resultrow['lainassa']
        );
        
        $terr_edit_info = array(
            'operation'	=> 'delete',
            'key'		=> $terr_nbr,
            'data_old'	=> $terr_data_old,
            'data_new'	=> null
        );
        
        
        //Päivityksen tiedot muistiin
        $undo_redo_stack->execute($terr_edit_info);
        /** SERIALIZE **/
        $_SESSION['undo_redo_terr_edit'] = serialize($undo_redo_stack);
        
        //Poista tietue
        $this->Maintenance_model->delete($terr_nbr);
        
        //Palataan päänäytölle siinä tilassa, kuin se oli ennen päivitystä
        $this->display($this->session->userdata('sort_by'),
            $this->session->userdata('sort_order'),
            $this->session->userdata('code_sel'),
            $this->session->userdata('filter'));
    }

    public function undo() 
    {
        /** UNSERIALIZE **/
        $undo_redo_stack = unserialize($_SESSION['undo_redo_terr_edit']);
        
        if ($undo_redo_stack->can_undo()) {
            $edit_info_data = $undo_redo_stack->undo();
            
            switch ($edit_info_data['operation']) {
                case "edit":
                    //Peru muutos
                    $update_data = array(
                      'alue_detail' => $edit_info_data['data_old']['alue_detail'],
                      'alue_location' => $edit_info_data['data_old']['alue_location'],
                      'alue_taloudet' => $edit_info_data['data_old']['alue_taloudet']
                    );
                    $this->Territory_model->update($update_data, $edit_info_data['key']);
                    break;
                    
                case "add":
                    //Poista lisätty alue
                    $this->Maintenance_model->delete($edit_info_data['key']);
                    break;
                    
                case "delete":
                    //Lisää poistettu alue
                    $insert_data = array(
                      'alue_code' => $edit_info_data['key'],
                      'alue_detail' => $edit_info_data['data_old']['alue_detail'],
                      'alue_location' => $edit_info_data['data_old']['alue_location'],
                      'alue_taloudet' => $edit_info_data['data_old']['alue_taloudet'],
                      'alue_lastdate'	=> $edit_info_data['data_old']['alue_lastdate'],
                      'alue_group'	=> $edit_info_data['data_old']['alue_group'],
                      'lainassa'  	=> $edit_info_data['data_old']['lainassa']
                    );
                    $this->Maintenance_model->insert($insert_data);
                    break;
                    
                default:
                    break;
            } // switch
            
         } else {
            $this->session->set_flashdata("error", "Can't undo.");
        }
        
        /** SERIALIZE **/
        $_SESSION['undo_redo_terr_edit'] = serialize($undo_redo_stack);
       
        //Palataan päänäytölle siinä tilassa, kuin se oli ennen päivitystä
        $this->display($this->session->userdata('sort_by'),
            $this->session->userdata('sort_order'),
            $this->session->userdata('code_sel'),
            $this->session->userdata('filter'));
    }

    public function redo()
    {
        /** UNSERIALIZE **/
        $undo_redo_stack = unserialize($_SESSION['undo_redo_terr_edit']);
        
        if ($undo_redo_stack->can_redo()) {
            $edit_info_data = $undo_redo_stack->redo();
            
            switch ($edit_info_data['operation']) {
                case "edit":
                    //Palauta muutos
                    $update_data = array(
                       'alue_detail' => $edit_info_data['data_new']['alue_detail'],
                       'alue_location' => $edit_info_data['data_new']['alue_location'],
                       'alue_taloudet' => $edit_info_data['data_new']['alue_taloudet']
                    );
                    $this->Territory_model->update($update_data, $edit_info_data['key']);
                    break;
                    
                case "add":
                    $insert_data = array(
                       'alue_code' => $edit_info_data['key'],
                       'alue_detail' => $edit_info_data['data_new']['alue_detail'],
                       'alue_location' => $edit_info_data['data_new']['alue_location'],
                       'alue_taloudet' => $edit_info_data['data_new']['alue_taloudet'],
                       'alue_lastdate'	=> $edit_info_data['data_new']['alue_lastdate'],
                       'alue_group'	=> $edit_info_data['data_new']['alue_group'],
                       'lainassa'  	=> $edit_info_data['data_new']['lainassa']
                    );
                    $this->Maintenance_model->insert($insert_data);
                    break;
                    
                case "delete":
                    $this->Maintenance_model->delete($edit_info_data['key']);
                    break;
                    
                default:
                    break;
            } // switch
        } else {
            $this->session->set_flashdata("error", "Can't redo.");
        }
        
        /** SERIALIZE **/
        $_SESSION['undo_redo_terr_edit'] = serialize($undo_redo_stack);
        
        //Palataan päänäytölle siinä tilassa, kuin se oli ennen päivitystä
        $this->display($this->session->userdata('sort_by'),
            $this->session->userdata('sort_order'),
            $this->session->userdata('code_sel'),
            $this->session->userdata('filter'));
    }
    
    public function index()
    {
        $this->display();
    }
    
}
