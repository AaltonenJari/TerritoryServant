<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Person_controller extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        
        // Load the maintenance model to make it available
        // to *all* of the controller's actions
        $this->load->model('Person_model');
        $this->load->model('Group_model');
        $this->load->model('UndoRedoStack');
    }
    
    public function display($sort_by = 'person_lastname', $sort_order = 'asc', $group_limit_sel = '0', $filter = '') 
    {
        //State variables for person_view
        if (($sort_by != $this->session->userdata('sort_by')) ||
            ($sort_order != $this->session->userdata('sort_order')) ||
            ($group_limit_sel != $this->session->userdata('group_limit_sel')) ||
            ($filter != $this->session->userdata('filter'))) {
            
                //Jos jokin tilamuuttuja muuttuu, poistetaan virheteksti näkyvistä
                if(isset($_SESSION['error'])){
                    unset($_SESSION['error']);
                }
            }
        //Tallenna näytön uusi tila
        $person_view_state_data = array(
            'sort_by'         => $sort_by,
            'sort_order'      => $sort_order,
            'group_limit_sel' => $group_limit_sel,
            'filter'          => $filter
        );
        $this->session->set_userdata($person_view_state_data);
        
        //Hakuparametrit näytölle
        $data['display_fields'] = array(
            'person_id'		=> 'tunnus',
            'person_name'		=> 'etunimi',
            'person_lastname'	=> 'sukunimi',
            'group'	        => 'ryhmä',
            'person_leader'	=> 'ryhmänvalvoja',
            'person_show'	=> 'näytetään',
            'event_count'	=> 'poisto'
        );
        
        //Hakuparametrit kantaan
        $data['database_fields'] = array(
            'person_id'		=> 'tunnus',
            'person_name'		=> 'etunimi',
            'person_lastname'	=> 'sukunimi',
            'person_group'	=> 'palvelusryhmä',
            'group_name'	=> 'ryhmänimi',
            'person_leader'	=> 'ryhmänvalvoja',
            'person_show'	=> 'näytetään',
            'event_count'	=> 'määrä'
        );

        //Korjaa ääkköset takaisin
        $filter = urldecode($filter);
        
        //Hae tiedot
        $results = $this->Person_model->search($data['database_fields'], $sort_by, $sort_order, $group_limit_sel);
        //Tiedot näytölle sopiviksi
        $data['persons'] = $this->create_displayrows($results);
        
        $data['num_results'] = $results['num_rows'];
        
        //Parameters back to view page
        $data['sort_by'] = $sort_by;
        $data['sort_order'] = $sort_order;
        $data['group_limit_sel'] = $group_limit_sel;
        $data['filter'] = $filter;
        
        //Hae ryhmien nimet
        $group_fields = array(
            'group_id'		=> 'tunnus',
            'group_name'	=> 'Ryhmän nimi'
        );
        $group_results = $this->Group_model->search($group_fields, 'group_id', 'ASC');
        
        //Muokkaa haetut tiedot näytölle sopiviksi
        $data['groups'] = $this->create_group_rows($group_results);
        
        //Rajoitin valitsimen koodit
        $group_limit_codes = array();
        $group_limit_codes['Kaikki paitsi ei-aktiiviset'] = 'A'; //Kaikki paitsi ei-aktiiviset
        $group_limit_codes['Kaikki jotka on ryhmissä'] = 'B'; //Kaikki paitsi ei-aktiiviset ja ne joilla ei ryhmää
        $group_limit_codes['Ei-aktiiviset'] = 'X'; //ei-aktiiviset 
        
        foreach ($group_results['rows'] as $group_row) {
            $name_delim = ' = ';
            $key = $group_row->group_id . $name_delim . $group_row->group_name;
            $group_limit_codes[$key] = $group_row->group_id;
        }
        $data['group_limit_codes'] = $group_limit_codes;
        
        //Ryhmänvalvoja-valitsin
        $overseers = array(
            '0'		=> ' ',
            '1'		=> 'ryhmänvalvoja',
            '2'	    => 'ryhmänvalvojan apulainen'
        );
        $data['overseers'] = $overseers;
        
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
            if (!isset($_SESSION['undo_redo_person_edit'])) {
                /** Initialize **/
                $undo_redo_stack = new UndoRedoStack();
            } else {
                /** UNSERIALIZE **/
                $undo_redo_stack = unserialize($_SESSION['undo_redo_person_edit']);
            }
        }
        
        $data['can_undo'] = $undo_redo_stack->can_undo();
        $data['can_redo'] = $undo_redo_stack->can_redo();
        
        /** SERIALIZE **/
        $_SESSION['undo_redo_person_edit'] = serialize($undo_redo_stack);
                
        $this->load->view('person_view', $data);
    }
 
    private function create_displayrows($results)
    {
        $r = array();
        foreach ($results['rows'] as $fetched_row) {
            
            $resultrow = new stdClass;
            foreach ($fetched_row as $key=>$value) {
                switch ($key) {
                    case "person_id":
                    case "person_name":
                    case "person_lastname":
                    case "person_leader":
                    case "person_show":
                    case "event_count":
                        $resultrow->$key = $value;
                        break;
                        
                    case "group_name":
                        $delim = ' = ';
                        $resultrow->group = $fetched_row->person_group . $delim . $value;
                        break;
                        
                     default:
                        break;
                } // switch
            } // foreach results row
            $r[] = $resultrow;
        }
        return $r;
    }
    
    private function create_group_rows($results)
    {
        $options = array();
        $options['inactive'] = '0 = Ei aktiivinen'; //Älä näytä, kun alueita merkitään
        
        foreach ($results['rows'] as $group_row) {
            $resultrow = array();
            foreach ($group_row as $key=>$value) {
                switch ($key) {
                    case "group_id":
                        break;
                        
                    case "group_name":
                        $name_delim = ' = ';
                        $resultrow['show_name'] = $group_row->group_id . $name_delim . $value;
                        break;
                        
                    default:
                        break;
                } // switch
            } // foreach person_row
            $options[$resultrow['show_name']] = $resultrow['show_name'];
        }
        
        return $options;
    }
    
    public function update_rows()
    {
        $field_person_ids = $this->input->post('person_id_old');
        
        $field_person_names = $this->input->post('person_name');
        $field_old_person_names = $this->input->post('person_name_old');
        
        $field_person_lastnames = $this->input->post('person_lastname');
        $field_old_person_lastnames = $this->input->post('person_lastname_old');
        
        $field_person_groups = $this->input->post('group_id');
        $field_old_person_groups = $this->input->post('group_id_old');
  
        $field_person_leaders = $this->input->post('person_leader');
        $field_old_person_leaders = $this->input->post('person_leader_old');
        
        $field_person_shows = $this->input->post('person_show');
        $field_old_person_shows = $this->input->post('person_show_old');
        
        $r = array();
        
        /** UNSERIALIZE **/
        $undo_redo_stack = unserialize($_SESSION['undo_redo_person_edit']);
        
        for ($i = 0; $i < sizeof($field_person_ids); $i++) {
            if (($field_person_names[$i] != $field_old_person_names[$i]) ||
                ($field_person_lastnames[$i] != $field_old_person_lastnames[$i]) ||
                ($field_person_groups[$i] != $field_old_person_groups[$i]) ||
                ($field_person_leaders[$i] != $field_old_person_leaders[$i]) ||
                ($field_person_shows[$i] != $field_old_person_shows[$i])) {
                    
                    if ($field_person_groups[$i] == '0' || $field_person_groups[$i] == '5') {
                        //Jos ei-aktiivinen tai ei ryhmää, ei voi olla ryhmänvalvoja.
                        $field_person_leaders[$i] = '0';
                    }
                    
                    if ($field_person_shows[$i] != '0') {
                        //Kenttä 'show' voi olla joko 0 tai 1
                        $field_person_shows[$i] = '1';
                    }
                    $update_data = array(
                        'person_name'		=> $field_person_names[$i],
                        'person_lastname'	=> $field_person_lastnames[$i],
                        'person_group'	    => $field_person_groups[$i],
                        'person_leader' 	=> $field_person_leaders[$i],
                        'person_show'	    => $field_person_shows[$i]
                    );
                    $this->Person_model->update($update_data, $field_person_ids[$i]);
                     
                    //Tiedot undo/redo -toimintoa varten
                    $update_data_old = array(
                        'person_name'		=> $field_old_person_names[$i],
                        'person_lastname'	=> $field_old_person_lastnames[$i],
                        'person_group'	    => $field_old_person_groups[$i],
                        'person_leader' 	=> $field_old_person_leaders[$i],
                        'person_show'	    => $field_old_person_shows[$i]
                    );
                    
                    $edit_info = array(
                        'operation'	=> 'edit',
                        'key'		=> $field_person_ids[$i],
                        'data_old'	=> $update_data_old,
                        'data_new'	=> $update_data
                    );
                    //Päivityksen tiedot muistiin
                    $undo_redo_stack->execute($edit_info);
                    $_SESSION['undo_redo_person_edit'] = serialize($undo_redo_stack);
                }
        }
        
        /** SERIALIZE **/
        $_SESSION['undo_redo_person_edit'] = serialize($undo_redo_stack);
        
        //Palataan päänäytölle siinä tilassa, kuin se oli ennen päivitystä
        $this->display($this->session->userdata('sort_by'),
            $this->session->userdata('sort_order'),
            $this->session->userdata('group_limit_sel'),
            $this->session->userdata('filter'));
        
        return ;
    }
    
    public function update($person_id, $field_name, $field_value, $filter = '')
    {
        //State variables of person_view
        $person_view_state_data = array(
            'filter'          => $filter
        );
        $this->session->set_userdata($person_view_state_data);
        
        //Haetaan henkilön tiedot kannasta
        $columns = array(
            'person_name',
            'person_lastname',
            'person_group',
            'person_leader',
            'person_show'
        );
        $resultrow = $this->Person_model->get_row_by_key($columns, $person_id);
        
        $update_data = array();
        
        foreach ($resultrow as $key=>$value) {
            switch ($key) {
                case "person_name":
                    if ($field_name == "person_name") {
                        if ($field_value == '0') {
                            $update_data['person_name'] = " ";
                        } else {
                            $update_data['person_name'] = $field_value;
                        }
                    } else {
                        $update_data['person_name'] = $value;
                    }
                    break;
                    
                case "person_lastname":
                    if ($field_name == "person_lastname") {
                        if ($field_value == '0') {
                            $update_data['person_lastname'] = " ";
                        } else {
                            $update_data['person_lastname'] = $field_value;
                        }
                    } else {
                        $update_data['person_lastname'] = $value;
                    }
                    break;
                
                case "person_group":
                    if ($field_name == "group_id") {
                        $update_data['person_group'] = $field_value;
                    } else {
                        $update_data['person_group'] = $value;
                    }
                    break;
                
                case "person_leader":
                    if ($update_data['person_group'] == '0' || $update_data['person_group'] == '5') {
                        //Jos ei-aktiivinen tai ei ryhmää, ei voi olla ryhmänvalvoja eikä apulainen.
                        $update_data['person_leader'] = '0';
                    } else {
                        if ($field_name == "person_leader") {
                            $update_data['person_leader'] = $field_value;
                        } else {
                            $update_data['person_leader'] = $value;
                        }
                    }
                    break;
                
                case "person_show":
                    if ($field_name == "person_show") {
                        $update_data['person_show'] = $field_value;
                    } else {
                        $update_data['person_show'] = $value;
                    }
                    break;
                
                default:
                    break;
            } // switch
        } //foreach $resultrow
        
        /** UNSERIALIZE **/
        $undo_redo_stack = unserialize($_SESSION['undo_redo_person_edit']);
        
        //Päivitys
        $this->Person_model->update($update_data, $person_id);
        
        //Tiedot undo/redo -toimintoa varten
        $update_data_old = array(
            'person_name'		=> $resultrow['person_name'],
            'person_lastname'	=> $resultrow['person_lastname'],
            'person_group'	    => $resultrow['person_group'],
            'person_leader' 	=> $resultrow['person_leader'],
            'person_show'	    => $resultrow['person_show']
        );
        
        $edit_info = array(
            'operation'	=> 'edit',
            'key'		=> $person_id,
            'data_old'	=> $update_data_old,
            'data_new'	=> $update_data
        );
 
        //Päivityksen tiedot muistiin
        $undo_redo_stack->execute($edit_info);
        
        $_SESSION['undo_redo_person_edit'] = serialize($undo_redo_stack);
        
        
        //Palataan päänäytölle siinä tilassa, kuin se oli ennen päivitystä
        $this->display($this->session->userdata('sort_by'),
            $this->session->userdata('sort_order'),
            $this->session->userdata('group_limit_sel'),
            $this->session->userdata('filter'));
    
        return ;
    }
    
    public function insert()
    {
         $data = array(
            'person_name'		=> '',
            'person_lastname'	=> '',
            'person_group'	=> '5 = Ei Ryhmää',
            'person_leader'	=> '0',
            'person_show'	=> '1'
        );
        
         //Hae ryhmien nimet
         $group_fields = array(
             'group_id'		=> 'tunnus',
             'group_name'	=> 'Ryhmän nimi'
         );
         $group_results = $this->Group_model->search($group_fields, 'group_id', 'ASC');
         
         //Muokkaa haetut tiedot näytölle sopiviksi
         $data['groups'] = $this->create_group_rows($group_results);
         $data['person_group_string'] = '5 = Ei Ryhmää';
         
         
         $overseers = array(
             '0'		=> ' ',
             '1'		=> 'ryhmänvalvoja',
             '2'	    => 'ryhmänvalvojan apulainen'
         );
         $data['overseers'] = $overseers;
         $data['overseers_string'] = ' ';
         
        $this->load->view('person_insert', $data);
    }
    
    public function check_update($filter = '')
    {
        //State variables of person_view
        $person_view_state_data = array(
            'filter'          => $filter
        );
        $this->session->set_userdata($person_view_state_data);

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
                $msg = "Tunnistamaton toiminto";
                $num_vars = count( explode( '###', http_build_query($_POST, '', '###') ) );
                $max_num_vars = ini_get('max_input_vars');
                if ($num_vars > $max_num_vars) {
                    $msg .= " Input-parametreja on enemmän kuin " . $max_num_vars;
                }
                $this->session->set_flashdata('error', $msg);
                
                //Palataan päänäytölle siinä tilassa, kuin se oli ennen päivitystä
                $this->display($this->session->userdata('sort_by'),
                    $this->session->userdata('sort_order'),
                    $this->session->userdata('group_limit_sel'),
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
                    $this->session->userdata('group_limit_sel'),
                    $this->session->userdata('filter'));
                break;
                
            default:
                $msg = "Tunnistamaton toiminto";
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
        if (empty($this->input->post('person_name')) && empty($this->input->post('person_lastname'))) {
            $this->session->set_flashdata('error', 'Henkilön etu- ja sukunimi ovat kumpikin tyhjiä.');
            return false;
        }
        //Tarkista, onko henkilön tiedot jo kannassa
        $found = $this->Person_model->get_id_by_name($this->input->post('person_name'), $this->input->post('person_lastname'));
        if ($found >= 0)  {
            if ($this->session->userdata('namePresentation') == "0") {
                //0 = firstname lsatname, 1 = lastmame, firstname; (default)
                $name_delim = ' ';
                $name = $this->input->post('person_name') . $name_delim . $this->input->post('person_lastname');
            } else {
                $name_delim = ', ';
                $name = $this->input->post('person_lastname') . $name_delim . $this->input->post('person_name');
            }
            
            $msg = "Ei voi lisätä. Nimi " . $name . ", on jo kannassa.";
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
        $undo_redo_stack = unserialize($_SESSION['undo_redo_person_edit']);
        
        $person_show = $this->input->post('person_show');
        if ($person_show != '0') {
            //Kenttä 'show' voi olla joko 0 tai 1
            $person_show = '1';
        }
        
        $insert_data = array(
            'person_name'		=> $this->input->post('person_name'),
            'person_lastname'	=> $this->input->post('person_lastname'),
            'person_group'	    => $this->input->post('person_group'),
            'person_leader'	    => $this->input->post('person_leader'),
            'person_show'	    => $person_show
        );
        
        //Lisää uusi
        $this->Person_model->insert($insert_data);
        
        //Tiedot undo/redo -toimintoa varten
        $person_id = $this->Person_model->get_id_by_name($this->input->post('person_name'), $this->input->post('person_lastname'));
        
        $edit_info = array(
            'operation'	=> 'add',
            'key'		=> $person_id,
            'data_old'	=> null,
            'data_new'	=> $insert_data
        );
        
        //Lisäyksen tiedot muistiin
        $undo_redo_stack->execute($edit_info);
        
         
        /** SERIALIZE **/
        $_SESSION['undo_redo_person_edit'] = serialize($undo_redo_stack);
        
        $this->display($this->session->userdata('sort_by'),
            $this->session->userdata('sort_order'),
            $this->session->userdata('group_limit_sel'),
            $this->session->userdata('filter'));

    }
        
    public function delete($key_id, $filter = '')
    {
        //State variables of person_view
        $person_view_state_data = array(
            'filter'          => $filter
        );
        $this->session->set_userdata($person_view_state_data);
        
        /** UNSERIALIZE **/
        $undo_redo_stack = unserialize($_SESSION['undo_redo_person_edit']);
        
        //Haetaan poistettavan alueen tiedot
        $columns = array(
            'person_name',
            'person_lastname',
            'person_group',
            'person_leader',
            'person_show'
         );
        $resultrow = $this->Person_model->get_row_by_key($columns, $key_id);
        
        
        //Tiedot undo/redo -toimintoa varten
        $data_old = array(
            'person_name'	=> $resultrow['person_name'],
            'person_lastname'	=> $resultrow['person_lastname'],
            'person_group'	=> $resultrow['person_group'],
            'person_leader'	=> $resultrow['person_leader'],
            'person_show'  	=> $resultrow['person_show']
        );
        
        $edit_info = array(
            'operation'	=> 'delete',
            'key'		=> $key_id,
            'data_old'	=> $data_old,
            'data_new'	=> null
        );
        
        //Päivityksen tiedot muistiin
        $undo_redo_stack->execute($edit_info);
        /** SERIALIZE **/
        $_SESSION['undo_redo_person_edit'] = serialize($undo_redo_stack);
        
        //Poista tietue
        $this->Person_model->delete($key_id);
        
        //Palataan päänäytölle siinä tilassa, kuin se oli ennen päivitystä
        $this->display($this->session->userdata('sort_by'),
            $this->session->userdata('sort_order'),
            $this->session->userdata('group_limit_sel'),
            $this->session->userdata('filter'));
    }

    public function undo() 
    {
        /** UNSERIALIZE **/
        $undo_redo_stack = unserialize($_SESSION['undo_redo_person_edit']);
        
        if ($undo_redo_stack->can_undo()) {
            $edit_info_data = $undo_redo_stack->undo();
            
            switch ($edit_info_data['operation']) {
                case "edit":
                    //Peru muutos
                    $update_data = array(
                      'person_name' => $edit_info_data['data_old']['person_name'],
                      'person_lastname' => $edit_info_data['data_old']['person_lastname'],
                      'person_group' => $edit_info_data['data_old']['person_group'],
                      'person_leader' => $edit_info_data['data_old']['person_leader'],
                      'person_show' => $edit_info_data['data_old']['person_show']
                    );
                    $this->Person_model->update($update_data, $edit_info_data['key']);
                    break;
  
                case "add":
                    //Poista lisätty rivi
                    $person_id = $this->Person_model->get_id_by_name($edit_info_data['data_new']['person_name'], $edit_info_data['data_new']['person_lastname']);
                    $this->Person_model->delete($person_id);
                    break;
                    
                case "delete":
                    //Lisää poistettu rivi
                    $insert_data = array(
                      'person_name' => $edit_info_data['data_old']['person_name'],
                      'person_lastname' => $edit_info_data['data_old']['person_lastname'],
                      'person_group' => $edit_info_data['data_old']['person_group'],
                      'person_leader' => $edit_info_data['data_old']['person_leader'],
                      'person_show' => $edit_info_data['data_old']['person_show']
                    );
                    $this->Person_model->insert($insert_data);
                    break;
                    
                default:
                    break;
            } // switch
            
         } else {
            $this->session->set_flashdata("error", "Can't undo.");
        }
        
        /** SERIALIZE **/
        $_SESSION['undo_redo_person_edit'] = serialize($undo_redo_stack);
       
        //Palataan päänäytölle siinä tilassa, kuin se oli ennen päivitystä
        $this->display($this->session->userdata('sort_by'),
            $this->session->userdata('sort_order'),
            $this->session->userdata('group_limit_sel'),
            $this->session->userdata('filter'));
    }

    public function redo()
    {
        /** UNSERIALIZE **/
        $undo_redo_stack = unserialize($_SESSION['undo_redo_person_edit']);
        
        if ($undo_redo_stack->can_redo()) {
            $edit_info_data = $undo_redo_stack->redo();
            
            switch ($edit_info_data['operation']) {
                case "edit":
                    //Palauta muutos
                    $update_data = array(
                       'person_name' => $edit_info_data['data_new']['person_name'],
                       'person_lastname' => $edit_info_data['data_new']['person_lastname'],
                       'person_group' => $edit_info_data['data_new']['person_group'],
                       'person_leader' => $edit_info_data['data_new']['person_leader'],
                       'person_show' => $edit_info_data['data_new']['person_show']
                    );
                    $this->Person_model->update($update_data, $edit_info_data['key']);
                    break;
                    
                case "add":
                    $insert_data = array(
                       'person_name' => $edit_info_data['data_new']['person_name'],
                       'person_lastname' => $edit_info_data['data_new']['person_lastname'],
                       'person_group' => $edit_info_data['data_new']['person_group'],
                       'person_leader' => $edit_info_data['data_new']['person_leader'],
                       'person_show' => $edit_info_data['data_new']['person_show']
                    );
                    $this->Person_model->insert($insert_data);
                    break;
                    
                case "delete":
                    $person_id = $this->Person_model->get_id_by_name($edit_info_data['data_old']['person_name'], $edit_info_data['data_old']['person_lastname']);
                    $this->Person_model->delete($person_id);
                    break;
                    
                default:
                    break;
            } // switch
        } else {
            $this->session->set_flashdata("error", "Can't redo.");
        }
        
        /** SERIALIZE **/
        $_SESSION['undo_redo_person_edit'] = serialize($undo_redo_stack);
        
        //Palataan päänäytölle siinä tilassa, kuin se oli ennen päivitystä
        $this->display($this->session->userdata('sort_by'),
            $this->session->userdata('sort_order'),
            $this->session->userdata('group_limit_sel'),
            $this->session->userdata('filter'));
    }
    
    public function index()
    {
        $this->display();
    }
    
}
