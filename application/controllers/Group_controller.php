<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Group_controller extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        
        // Load the maintenance model to make it available
        // to *all* of the controller's actions
        $this->load->model('Group_model');
        $this->load->model('UndoRedoStack');
    }
    
    public function display($sort_by = 'group_id', $sort_order = 'asc', $filter = '') 
    {
        //State variables for group_view
        if (($sort_by != $this->session->userdata('sort_by')) ||
            ($sort_order != $this->session->userdata('sort_order')) ||
            ($filter != $this->session->userdata('filter'))) {
                
                //Jos jokin tilamuuttuja muuttuu, poistetaan virheteksti näkyvistä
                if(isset($_SESSION['error'])){
                    unset($_SESSION['error']);
                }
            }
            
        //Tallenna näytön uusi tila
        $group_view_state_data = array(
            'sort_by'         => $sort_by,
            'sort_order'      => $sort_order,
            'filter'          => $filter
        );
        $this->session->set_userdata($group_view_state_data);
        
        //Hakuparametrit näytölle
        $data['display_fields'] = array(
            'group_id'		=> 'tunnus',
            'group_name'	=> 'nimi',
            'group_events' 	=> 'tapahtumia',
            'person_count'	=> 'poisto'
        );
        
        //Hakuparametrit kantaan
        $data['database_fields'] = array(
            'group_id'		=> 'tunnus',
            'group_name'	=> 'nimi',
            'group_events'	=> 'tapahtumia',
            'person_count'	=> 'määrä'
        );

        //Korjaa ääkköset takaisin
        $filter = urldecode($filter);
        
        //Hae tiedot
        $results = $this->Group_model->search($data['database_fields'], $sort_by, $sort_order);
        //Tiedot näytölle sooiviksi
        $data['groups'] = $this->create_displayrows($results);
        
        $data['num_results'] = $results['num_rows'];
        
        //Parameters back to view page
        $data['sort_by'] = $sort_by;
        $data['sort_order'] = $sort_order;
        $data['filter'] = $filter;
                
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
            if (!isset($_SESSION['undo_redo_group_edit'])) {
                /** Initialize **/
                $undo_redo_stack = new UndoRedoStack();
            } else {
                /** UNSERIALIZE **/
                $undo_redo_stack = unserialize($_SESSION['undo_redo_group_edit']);
            }
        }
        
        $data['can_undo'] = $undo_redo_stack->can_undo();
        $data['can_redo'] = $undo_redo_stack->can_redo();
        
        /** SERIALIZE **/
        $_SESSION['undo_redo_group_edit'] = serialize($undo_redo_stack);
        
        $this->load->view('group_view', $data);
    }
 
    private function create_displayrows($results)
    {
        $r = array();
        foreach ($results['rows'] as $fetched_row) {
            
            $resultrow = new stdClass;
            foreach ($fetched_row as $key=>$value) {
                switch ($key) {
                    case "group_id":
                    case "group_name":
                    case "group_events":
                    case "person_count":
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
        $field_group_ids = $this->input->post('group_id_old');
        
        $field_group_names = $this->input->post('group_name');
        $field_old_group_names = $this->input->post('group_name_old');
        
        $field_group_events = $this->input->post('group_events');
        $field_old_group_events = $this->input->post('group_events_old');
        
         
        $r = array();
        
        /** UNSERIALIZE **/
        $undo_redo_stack = unserialize($_SESSION['undo_redo_group_edit']);
        
        for ($i = 0; $i < sizeof($field_group_ids); $i++) {
            if (($field_group_names[$i] != $field_old_group_names[$i]) ||
                ($field_group_events[$i] != $field_old_group_events[$i])) {
                    
                    $update_data = array(
                        'group_name'		=> $field_group_names[$i],
                        'group_events'	    => $field_group_events[$i]
                    );
                    $this->Group_model->update($update_data, $field_group_ids[$i]);
                     
                    //Tiedot undo/redo -toimintoa varten
                    $update_data_old = array(
                        'group_name'		=> $field_old_group_names[$i],
                        'group_events'	    => $field_old_group_events[$i]
                    );
                    
                    $edit_info = array(
                        'operation'	=> 'edit',
                        'key'		=> $field_group_ids[$i],
                        'data_old'	=> $update_data_old,
                        'data_new'	=> $update_data
                    );
                    //Päivityksen tiedot muistiin
                    $undo_redo_stack->execute($edit_info);
                    $_SESSION['undo_redo_group_edit'] = serialize($undo_redo_stack);
                }
        }
        
        /** SERIALIZE **/
        $_SESSION['undo_redo_group_edit'] = serialize($undo_redo_stack);
        
        //Palataan päänäytölle siinä tilassa, kuin se oli ennen päivitystä
        $this->display($this->session->userdata('sort_by'),
            $this->session->userdata('sort_order'),
            $this->session->userdata('filter'));
        
        return ;
    }
    
    public function update($group_id, $field_name, $field_value, $filter = '')
    {
        //State variables of group_view
        $group_view_state_data = array(
            'filter'          => $filter
        );
        $this->session->set_userdata($group_view_state_data);
        
        //Haetaan henkilön tiedot kannasta
        $columns = array(
            'group_name',
            'group_events'
        );
        $resultrow = $this->Group_model->get_row_by_key($columns, $group_id);
        
        $update_data = array();
        
        foreach ($resultrow as $key=>$value) {
            switch ($key) {
                case "group_name":
                    if ($field_name == "group_name") {
                        if ($field_value == '0') {
                            $update_data['group_name'] = " ";
                        } else {
                            $update_data['group_name'] = $field_value;
                        }
                    } else {
                        $update_data['group_name'] = $value;
                    }
                    break;
                    
                case "group_events":
                    if ($field_name == "group_events") {
                        $update_data['group_events'] = $field_value;
                    } else {
                        $update_data['group_events'] = $value;
                    }
                    break;
                
                default:
                    break;
            } // switch
        } //foreach $resultrow
        
        /** UNSERIALIZE **/
        $undo_redo_stack = unserialize($_SESSION['undo_redo_group_edit']);
        
        //Päivitys
        $this->Group_model->update($update_data, $group_id);
        
        //Tiedot undo/redo -toimintoa varten
        $update_data_old = array(
            'group_name'	    => $resultrow['group_name'],
            'group_events'	    => $resultrow['group_events']
        );
        
        $edit_info = array(
            'operation'	=> 'edit',
            'key'		=> $group_id,
            'data_old'	=> $update_data_old,
            'data_new'	=> $update_data
        );
 
        //Päivityksen tiedot muistiin
        $undo_redo_stack->execute($edit_info);
        
        $_SESSION['undo_redo_group_edit'] = serialize($undo_redo_stack);
        
        
        //Palataan päänäytölle siinä tilassa, kuin se oli ennen päivitystä
        $this->display($this->session->userdata('sort_by'),
            $this->session->userdata('sort_order'),
            $this->session->userdata('filter'));
    
        return ;
    }
    
    public function insert()
    {
         $data = array(
            'group_name'	=> '',
            'group_events'	=> '0'
        );
        
        $this->load->view('group_insert', $data);
    }
    
    public function check_update($filter = '')
    {
        //State variables of group_view
        $group_view_state_data = array(
            'filter'          => $filter
        );
        $this->session->set_userdata($group_view_state_data);

        $action = $this->input->post('action');
        switch ($action) {
            case "Päivitä":
            case "Update":
                $this->update_rows();
                break;
                
            case "Lisää":
            case "Add":
                $this->insert();
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
                    $this->insert();
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
                
                $this->insert();
                break;
        } // switch
        
        return;
    }
    public function verify_update() 
    {
        if (empty($this->input->post('group_name'))) {
            $this->session->set_flashdata('error', 'Ryhmän nimi on tyhjä.');
            return false;
        }
        //Tarkista, onko henkilön tiedot jo kannassa
        $found = $this->Group_model->get_id_by_name($this->input->post('group_name'));
        if ($found >= 0)  {
            $name = $this->input->post('group_name');
            $msg = "Ei voi lisätä. Ryhmän nimi [" . $name . "] on jo kannassa.";
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
        $undo_redo_stack = unserialize($_SESSION['undo_redo_group_edit']);
        
        $insert_data = array(
            'group_name'	=> $this->input->post('group_name'),
            'group_events'	=> $this->input->post('group_events')
        );
        
        //Lisää uusi
        $this->Group_model->insert($insert_data);
        
        //Tiedot undo/redo -toimintoa varten
        $group_id = $this->Group_model->get_id_by_name($this->input->post('group_name'));
        
        $edit_info = array(
            'operation'	=> 'add',
            'key'	=> $group_id,
            'data_old'	=> null,
            'data_new'	=> $insert_data
        );
        
        //Lisäyksen tiedot muistiin
        $undo_redo_stack->execute($edit_info);
        
         
        /** SERIALIZE **/
        $_SESSION['undo_redo_group_edit'] = serialize($undo_redo_stack);
        
        $this->display($this->session->userdata('sort_by'),
            $this->session->userdata('sort_order'),
            $this->session->userdata('filter'));

    }
        
    public function delete($key_id, $filter = '')
    {
        //State variables of group_view
        $group_view_state_data = array(
            'filter'          => $filter
        );
        $this->session->set_userdata($group_view_state_data);
        
        /** UNSERIALIZE **/
        $undo_redo_stack = unserialize($_SESSION['undo_redo_group_edit']);
        
        //Haetaan poistettavan alueen tiedot
        $columns = array(
            'group_name',
            'group_events'
         );
        $resultrow = $this->Group_model->get_row_by_key($columns, $key_id);
        
        
        //Tiedot undo/redo -toimintoa varten
        $data_old = array(
            'group_name'	=> $resultrow['group_name'],
            'group_events' 	=> $resultrow['group_events']
        );
        
        $edit_info = array(
            'operation'	=> 'delete',
            'key'	=> $key_id,
            'data_old'	=> $data_old,
            'data_new'	=> null
        );
        
        //Päivityksen tiedot muistiin
        $undo_redo_stack->execute($edit_info);
        /** SERIALIZE **/
        $_SESSION['undo_redo_group_edit'] = serialize($undo_redo_stack);
        
        //Poista tietue
        $this->Group_model->delete($key_id);
        
        //Palataan päänäytölle siinä tilassa, kuin se oli ennen päivitystä
        $this->display($this->session->userdata('sort_by'),
            $this->session->userdata('sort_order'),
            $this->session->userdata('filter'));
    }

    public function undo() 
    {
        /** UNSERIALIZE **/
        $undo_redo_stack = unserialize($_SESSION['undo_redo_group_edit']);
        
        if ($undo_redo_stack->can_undo()) {
            $edit_info_data = $undo_redo_stack->undo();
            
            switch ($edit_info_data['operation']) {
                case "edit":
                    //Peru muutos
                    $update_data = array(
                      'group_name' => $edit_info_data['data_old']['group_name'],
                      'group_events' => $edit_info_data['data_old']['group_events']
                    );
                    $this->Group_model->update($update_data, $edit_info_data['key']);
                    break;
                    
                case "add":
                    //Poista lisätty rivi
                    $group_id = $this->Group_model->get_id_by_name($edit_info_data['data_new']['group_name']);
                    $this->Group_model->delete($group_id);
                    break;
                    
                case "delete":
                    //Lisää poistettu rivi
                    $insert_data = array(
                      'group_name' => $edit_info_data['data_old']['group_name'],
                      'group_events' => $edit_info_data['data_old']['group_events']
                    );
                    $this->Group_model->insert($insert_data);
                    break;
                    
                default:
                    break;
            } // switch
            
         } else {
            $this->session->set_flashdata("error", "Can't undo.");
        }
        
        /** SERIALIZE **/
        $_SESSION['undo_redo_group_edit'] = serialize($undo_redo_stack);
       
        //Palataan päänäytölle siinä tilassa, kuin se oli ennen päivitystä
        $this->display($this->session->userdata('sort_by'),
            $this->session->userdata('sort_order'),
            $this->session->userdata('filter'));
    }

    public function redo()
    {
        /** UNSERIALIZE **/
        $undo_redo_stack = unserialize($_SESSION['undo_redo_group_edit']);
        
        if ($undo_redo_stack->can_redo()) {
            $edit_info_data = $undo_redo_stack->redo();
            
            switch ($edit_info_data['operation']) {
                case "edit":
                    //Palauta muutos
                    $update_data = array(
                       'group_name' => $edit_info_data['data_new']['group_name'],
                       'group_events' => $edit_info_data['data_new']['group_events']
                    );
                    $this->Group_model->update($update_data, $edit_info_data['key']);
                    break;
                    
                case "add":
                    $insert_data = array(
                       'group_name' => $edit_info_data['data_new']['group_name'],
                       'group_events' => $edit_info_data['data_new']['group_events']
                    );
                    $this->Group_model->insert($insert_data);
                    break;
                    
                case "delete":
                    $group_id = $this->Group_model->get_id_by_name($edit_info_data['data_old']['group_name']);
                    $this->Group_model->delete($group_id);
                    break;
                    
                default:
                    break;
            } // switch
        } else {
            $this->session->set_flashdata("error", "Can't redo.");
        }
        
        /** SERIALIZE **/
        $_SESSION['undo_redo_group_edit'] = serialize($undo_redo_stack);
        
        //Palataan päänäytölle siinä tilassa, kuin se oli ennen päivitystä
        $this->display($this->session->userdata('sort_by'),
            $this->session->userdata('sort_order'),
            $this->session->userdata('filter'));
    }
    
    public function index()
    {
        $this->display();
    }
    
}
