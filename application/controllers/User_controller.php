<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User_controller extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        
        // Load the maintenance model to make it available
        // to *all* of the controller's actions
        $this->load->model('User_model');
        $this->load->model('Group_model');
        $this->load->model('UndoRedoStack');
    }
    
    public function display($sort_by = 'user_lastname', $sort_order = 'asc', $filter = '') 
    {
        //State variables for user_view
        if (($sort_by != $this->session->userdata('sort_by')) ||
            ($sort_order != $this->session->userdata('sort_order')) ||
            ($filter != $this->session->userdata('filter'))) {
            
                //Jos jokin tilamuuttuja muuttuu, poistetaan virheteksti näkyvistä
                if(isset($_SESSION['error'])){
                    unset($_SESSION['error']);
                }
            }
        //Tallenna näytön uusi tila
        $user_view_state_data = array(
            'sort_by'         => $sort_by,
            'sort_order'      => $sort_order,
            'filter'          => $filter
        );
        $this->session->set_userdata($user_view_state_data);
        
        //Hakuparametrit näytölle
        $data['display_fields'] = array(
            'user_id'			=> 'tunnus',
            'user_username'		=> 'käyttäjänimi',
            'user_firstname'	=> 'etunimi',
            'user_lastname'		=> 'sukunimi',
            'user_email'	    => 'sähköposti',
            'user_admin'		=> 'Käyttäjärooli'
        );
        
        //Hakuparametrit kantaan
        $data['database_fields'] = array(
            'user_id'			=> 'tunnus',
            'user_username'		=> 'käyttäjänimi',
            'user_firstname'	=> 'etunimi',
            'user_lastname'		=> 'sukunimi',
            'user_email'	    => 'sähköposti',
            'user_admin'		=> 'Käyttäjärooli'
        );

        //Korjaa ääkköset takaisin
        $filter = urldecode($filter);
        
        //Hae tiedot
        $results = $this->User_model->search($data['database_fields'], $sort_by, $sort_order);
        //Tiedot näytölle sopiviksi
        $data['users'] = $this->create_displayrows($results);
        
        $data['num_results'] = $results['num_rows'];
        
        //Parameters back to view page
        $data['sort_by'] = $sort_by;
        $data['sort_order'] = $sort_order;
        $data['filter'] = $filter;
        
        //Aseta optiot
        //Käyttäjärooli -valitsin
        $userRoleOptions = array(
            '0'     => 'Peruskäyttäjä',
            '1'     => 'Ylläpitäjä'
        );
        $data['userRoleOptions'] = $userRoleOptions;
        
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
            if (!isset($_SESSION['undo_redo_user_edit'])) {
                /** Initialize **/
                $undo_redo_stack = new UndoRedoStack();
            } else {
                /** UNSERIALIZE **/
                $undo_redo_stack = unserialize($_SESSION['undo_redo_user_edit']);
            }
        }
        
        $data['can_undo'] = $undo_redo_stack->can_undo();
        $data['can_redo'] = $undo_redo_stack->can_redo();
        
        /** SERIALIZE **/
        $_SESSION['undo_redo_user_edit'] = serialize($undo_redo_stack);
                
        $this->load->view('user_view', $data);
    }
 
    private function create_displayrows($results)
    {
        $r = array();
        foreach ($results['rows'] as $fetched_row) {
            
            $resultrow = new stdClass;
            foreach ($fetched_row as $key=>$value) {
                switch ($key) {
                    case "user_id":
                    case "user_username":
                    case "user_password":
                    case "user_firstname":
                    case "user_lastname":
                    case "user_email":
                    case "user_admin":
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
    
    public function update_profile($mode, $user_id, $filter = '')
    {
        //State variables of user_view
        $user_view_state_data = array(
            'filter'          => $filter
        );
        $this->session->set_userdata($user_view_state_data);
        
        //Haetaan henkilön tiedot kannasta
        $data['display_fields'] = array(
            'user_id'			=> 'Tunnus',
            'user_username'		=> 'Käyttäjänimi',
            'user_firstname'	=> 'Etunimi',
            'user_lastname'		=> 'Sukunimi',
            'user_email'	    => 'Sähköposti',
            'user_admin'	    => 'Käyttäjärooli'
        );
        
        $columns = array(
            'user_id',
            'user_username',
            'user_firstname',
            'user_lastname',
            'user_email',
            'user_admin'
        );
        $resultrow = $this->User_model->get_row_by_key($columns, $user_id);
        
        if ($resultrow === null) {
            $data['error_title'] = 'Käyttäjää ei löytynyt';
            $data['error_message'] = 'Valitse alla olevasta painikkeesta palataksesi pääsivulle.';
            $data['base_url'] = 'user_controller/display_frontpage';
            $this->load->view('common/territory_error_view', $data);
            return;
        }
        
        $data['user'] = $resultrow;
        
        //Aseta optiot
        //Käyttäjärooli -valitsin
        $userRoleOptions = array(
            '0'     => 'Peruskäyttäjä',
            '1'     => 'Ylläpitäjä'
        );
        $data['userRoleOptions'] = $userRoleOptions;
        
        $data['editing_mode'] = $mode;
        
        $this->load->view('update_profile', $data);
    }
    
    public function check_profile() 
    {
        $mode = $this->input->post('editing_mode');
        
        $userId = $this->input->post('user_id');
        $action = $this->input->post('action');
        switch ($action) {
            case "Päivitä":
                // set validation rules
                $rules = array(
                array('field' => 'djnimi',
                'label' => 'Kenellä',
                'rules' => 'callback_verify_update')
                ) ;
                // check input data
                $this->form_validation->set_rules($rules);
                
                if ($this->form_validation->run() == false) {
                    $this->update_profile($mode, $userId);
                } else {
                    $this->update($mode);
                }
                break;
                
            case "Paluu":
                if(isset($_SESSION['error'])){
                    unset($_SESSION['error']);
                }
                if ($mode == "admin") {
                    //Palataan päänäytölle siinä tilassa, kuin se oli ennen päivitystä
                    $this->display($this->session->userdata('sort_by'),
                        $this->session->userdata('sort_order'),
                        $this->session->userdata('filter'));
                } else {
                    //Palataan päänäytölle
                    $main_url = 'Location: ' . base_url("index.php/Territory_controller/display");
                    header($main_url);
                }
                break;
                
            default:
                $msg = "Tunnistamaton toiminto";
                $num_vars = count( explode( '###', http_build_query($_POST, '', '###') ) );
                $max_num_vars = ini_get('max_input_vars');
                if ($num_vars > $max_num_vars) {
                    $msg .= " Input-parametreja on enemmän kuin " . $max_num_vars;
                }
                $this->session->set_flashdata('error', $msg);
                
                $this->update_profile($mode, $userId);
                break;
        } // switch
        
    }
    
    public function verify_update()
    {
        if (empty($this->input->post('user_username'))) {
            $this->session->set_flashdata('error', 'Käyttäjätunnus ei saa olla tyhjä.');
            return false;
        }
        if (empty($this->input->post('user_firstname')) && empty($this->input->post('user_lastname'))) {
            $this->session->set_flashdata('error', 'Henkilön etu- ja sukunimi ovat kumpikin tyhjiä.');
            return false;
        }
        //Tarkista, onko käyttäjätunnus jo kannassa
        $found = $this->User_model->username_exists($this->input->post('user_username'));
        if ($found > 0)  {
            //Tarkista vielä, yritetäänkö muuttaa toiseksi, olemassaolevaksi käyttäjätunnukseksi
			$columns = array(
                'user_username'
            );
			$resultrow = $this->User_model->get_row_by_key($columns, $this->input->post('user_id'));
			if ($this->input->post('user_username') != $resultrow['user_username']) {
			    $msg = "Ei voi päivittää. Käyttäjätunnus " . $this->input->post('user_username') . " on käytössä.";
			    $this->session->set_flashdata('error', $msg);
			    return false;
			}
        }
        
        //Poistetaan aikaisemmin näkynyt virheteksti
        if(isset($_SESSION['error'])){
            unset($_SESSION['error']);
        }
        return true;
    }
    
    public function update_rows()
    {
        $field_user_ids = $this->input->post('user_id_old');
        
        $field_user_usernames = $this->input->post('user_username');
        $field_old_user_usernames = $this->input->post('user_username_old');

        $field_user_firstnames = $this->input->post('user_firstname');
        $field_old_user_firstnames = $this->input->post('user_firstname_old');
        
        $field_user_lastnames = $this->input->post('user_lastname');
        $field_old_user_lastnames = $this->input->post('user_lastname_old');
          
        $field_user_emails = $this->input->post('user_email');
        $field_old_user_emails = $this->input->post('user_email_old');
        
        $field_user_admins = $this->input->post('user_admin');
        $field_old_user_admins = $this->input->post('user_admin_old');
        
        $r = array();
        
        /** UNSERIALIZE **/
        $undo_redo_stack = unserialize($_SESSION['undo_redo_user_edit']);
        
        for ($i = 0; $i < sizeof($field_user_ids); $i++) {
            if (($field_user_usernames[$i] != $field_old_user_usernames[$i]) ||
                ($field_user_firstnames[$i] != $field_old_user_firstnames[$i]) ||
                ($field_user_lastnames[$i] != $field_old_user_lastnames[$i]) ||
                ($field_user_emails[$i] != $field_old_user_emails[$i]) ||
                ($field_user_admins[$i] != $field_old_user_admins[$i])) {
                    
                    $update_data = array(
                        'user_username'		=> $field_user_usernames[$i],
                        'user_firstname'	=> $field_user_firstnames[$i],
                        'user_lastname'		=> $field_user_lastnames[$i],
                        'user_email' 		=> $field_user_emails[$i],
                        'user_admin'	    => $field_user_admins[$i]
                    );
                    $this->User_model->update($update_data, $field_user_ids[$i]);
                     
                    //Tiedot undo/redo -toimintoa varten
                    $update_data_old = array(
                        'user_username'		=> $field_old_user_usernames[$i],
                        'user_firstname'	=> $field_old_user_firstnames[$i],
                        'user_lastname'		=> $field_old_user_lastnames[$i],
                        'user_email' 		=> $field_old_user_emails[$i],
                        'user_admin'	    => $field_old_user_admins[$i]
                    );
                    
                    $edit_info = array(
                        'operation'	=> 'edit',
                        'key'		=> $field_user_ids[$i],
                        'data_old'	=> $update_data_old,
                        'data_new'	=> $update_data
                    );
                    //Päivityksen tiedot muistiin
                    $undo_redo_stack->execute($edit_info);
                    $_SESSION['undo_redo_user_edit'] = serialize($undo_redo_stack);
                }
        }
        
        /** SERIALIZE **/
        $_SESSION['undo_redo_user_edit'] = serialize($undo_redo_stack);
        
        //Palataan päänäytölle siinä tilassa, kuin se oli ennen päivitystä
        $this->display($this->session->userdata('sort_by'),
            $this->session->userdata('sort_order'),
            $this->session->userdata('filter'));
        
        return ;
    }
    
    public function update_field($user_id, $field_name, $field_value, $filter = '')
    {
        //State variables of user_view
        $user_view_state_data = array(
            'filter'          => $filter
        );
        $this->session->set_userdata($user_view_state_data);
        
        //Haetaan henkilön tiedot kannasta
        $columns = array(
            'user_username',
            'user_firstname',
            'user_lastname',
            'user_email',
            'user_admin'
        );
        $resultrow = $this->User_model->get_row_by_key($columns, $user_id);
        
        $update_data = array();
        
        foreach ($resultrow as $key=>$value) {
            switch ($key) {
                 case "user_username":
                    if ($field_name == "user_username") {
                        $update_data['user_username'] = $field_value;
                    } else {
                        $update_data['user_username'] = $value;
                    }
                    break;

                case "user_firstname":
                    if ($field_name == "user_firstname") {
                        if ($field_value == '0') {
                            $update_data['user_firstname'] = " ";
                        } else {
                            $update_data['user_firstname'] = $field_value;
                        }
                    } else {
                        $update_data['user_firstname'] = $value;
                    }
                    break;
                    
                case "user_lastname":
                    if ($field_name == "user_lastname") {
                        if ($field_value == '0') {
                            $update_data['user_lastname'] = " ";
                        } else {
                            $update_data['user_lastname'] = $field_value;
                        }
                    } else {
                        $update_data['user_lastname'] = $value;
                    }
                    break;
                
                 case "user_email":
                    if ($field_name == "user_email") {
                        $update_data['user_email'] = $field_value;
                    } else {
                        $update_data['user_email'] = $value;
                    }
                    break;
                
                case "user_admin":
                    if ($field_name == "user_admin") {
                        $update_data['user_admin'] = $field_value;
                    } else {
                        $update_data['user_admin'] = $value;
                    }
                    break;
                
                default:
                    break;
            } // switch
        } //foreach $resultrow
        
        /** UNSERIALIZE **/
        $undo_redo_stack = unserialize($_SESSION['undo_redo_user_edit']);
        
        //Päivitys
        $this->User_model->update($update_data, $user_id);
        
        //Tiedot undo/redo -toimintoa varten
        $update_data_old = array(
            'user_username'		=> $resultrow['user_username'],
            'user_firstname'	=> $resultrow['user_firstname'],
            'user_lastname'		=> $resultrow['user_lastname'],
            'user_email' 		=> $resultrow['user_email'],
            'user_admin'	    => $resultrow['user_admin']
        );
        
        $edit_info = array(
            'operation'	=> 'edit',
            'key'		=> $user_id,
            'data_old'	=> $update_data_old,
            'data_new'	=> $update_data
        );
 
        //Päivityksen tiedot muistiin
        $undo_redo_stack->execute($edit_info);
        
        $_SESSION['undo_redo_user_edit'] = serialize($undo_redo_stack);
        
        
        //Palataan päänäytölle siinä tilassa, kuin se oli ennen päivitystä
        $this->display($this->session->userdata('sort_by'),
            $this->session->userdata('sort_order'),
            $this->session->userdata('filter'));
    
        return ;
    }
    
    public function update($mode) 
    {
        if ($mode == "admin") {
            /** UNSERIALIZE **/
            $undo_redo_stack = unserialize($_SESSION['undo_redo_user_edit']);
        }
        $user_id = $this->input->post('user_id');
        
        if ($mode == "admin") {
            //Haetaan henkilön vanhat tiedot kannasta
            $columns = array(
                'user_username',
                'user_password',
                'user_firstname',
                'user_lastname',
                'user_email',
                'user_admin'
            );
            $resultrow = $this->User_model->get_row_by_key($columns, $user_id);
            
            //Tiedot undo/redo -toimintoa varten
            $data_old = array();
            $data_old['user_username'] = $resultrow['user_username'];
            $data_old['user_firstname'] = $resultrow['user_firstname'];
            $data_old['user_lastname'] = $resultrow['user_lastname'];
            $data_old['user_email'] = $resultrow['user_email'];
            $data_old['user_admin'] = $resultrow['user_admin'];
            if (!empty($this->input->post('user_password'))) {
                $data_old['user_password'] = $resultrow['user_password'];
            }
        }
        
        //Päivitetään uudet tiedot
        $update_data = array();
        $update_data['user_username'] = $this->input->post('user_username');
        $update_data['user_firstname'] = $this->input->post('user_firstname');
        $update_data['user_lastname'] = $this->input->post('user_lastname');
        $update_data['user_email'] = $this->input->post('user_email');
        $update_data['user_admin'] = $this->input->post('user_admin');
        if (!empty($this->input->post('user_password'))) {
            $update_data['user_password'] = $this->input->post('user_password');
        }
        $this->User_model->update($update_data, $user_id);
        
        if ($mode == "admin") {
            $edit_info = array(
                'operation'	=> 'edit',
                'key'		=> $user_id,
                'data_old'	=> $data_old,
                'data_new'	=> $update_data
            );
            
            //Lisäyksen tiedot muistiin
            $undo_redo_stack->execute($edit_info);
            
            /** SERIALIZE **/
            $_SESSION['undo_redo_user_edit'] = serialize($undo_redo_stack);
            
            $this->display($this->session->userdata('sort_by'),
                $this->session->userdata('sort_order'),
                $this->session->userdata('filter'));
        } else {
            $this->update_profile($mode, $user_id);
        }
        
    }
    
    public function insert($mode = 'new')
    {
        if ($mode == 'editing') {
            $user_username = $this->input->post('user_username');
            $user_password = $this->input->post('user_password');
            $user_firstname = $this->input->post('user_firstname');
            $user_lastname = $this->input->post('user_lastname');
            $user_email = $this->input->post('user_email');
            $user_admin = $this->input->post('user_admin');
        } else {
            $user_username =  '';
            $user_password =  '';
            $user_firstname = '';
            $user_lastname =  '';
            $user_email = '';
            $user_admin = '0';
        }
        
         $data = array(
             'user_username'	=> $user_username,
             'user_password'	=> $user_password,
             'user_firstname'	=> $user_firstname,
             'user_lastname'	=> $user_lastname,
             'user_email'		=> $user_email,
             'user_admin'		=> $user_admin
        );
         
        $this->load->view('user_insert', $data);
    }
    
    public function check_update($filter = '')
    {
        //State variables of user_view
        $user_view_state_data = array(
            'filter'          => $filter
        );
        $this->session->set_userdata($user_view_state_data);

        $action = $this->input->post('action');
        switch ($action) {
            case "Päivitä":
            case "Update":
                $this->update_rows();
                break;
                
            case "Lisää":
            case "Add":
                $this->insert('new');
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
                  'rules' => 'callback_verify_insert')
                ) ;
                // check input data
                $this->form_validation->set_rules($rules);
                
                if ($this->form_validation->run() == false) {
                    $this->insert('editing');
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
                $msg = "Tunnistamaton toiminto";
                $num_vars = count( explode( '###', http_build_query($_POST, '', '###') ) );
                $max_num_vars = ini_get('max_input_vars');
                if ($num_vars > $max_num_vars) {
                    $msg .= " Input-parametreja on enemmän kuin " . $max_num_vars;
                }
                $this->session->set_flashdata('error', $msg);
                
                $this->insert('editing');
                break;
        } // switch
        
        return;
    }
    
    public function verify_insert() 
    {
        if (empty($this->input->post('user_username'))) {
            $this->session->set_flashdata('error', 'Käyttäjätunnus ei saa olla tyhjä.');
            return false;
        }
        if (empty($this->input->post('user_password'))) {
            $this->session->set_flashdata('error', 'Salasana ei saa olla tyhjä.');
            return false;
        }
        if (empty($this->input->post('user_firstname')) && empty($this->input->post('user_lastname'))) {
            $this->session->set_flashdata('error', 'Käyttäjän etu- ja sukunimi ovat kumpikin tyhjiä.');
            return false;
        }
        //Tarkista, onko käyttäjätunnus jo kannassa
        $found = $this->User_model->username_exists($this->input->post('user_username'));
        if ($found > 0)  {
            $msg = "Ei voi lisätä. Käyttäjätunnus " . $this->input->post('user_username') . " on käytössä.";
            $this->session->set_flashdata('error', $msg);
            return false;
        }
        //Tarkista, onko henkilön tiedot jo kannassa
        $found = $this->User_model->get_id_by_name($this->input->post('user_firstname'), $this->input->post('user_lastname'));
        if ($found >= 0)  {
            if ($this->session->userdata('namePresentation') == "0") {
                //0 = firstname lsatname, 1 = lastmame, firstname; (default)
                $name_delim = ' ';
                $name = $this->input->post('user_firstname') . $name_delim . $this->input->post('user_lastname');
            } else {
                $name_delim = ', ';
                $name = $this->input->post('user_lastname') . $name_delim . $this->input->post('user_firstname');
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
        $undo_redo_stack = unserialize($_SESSION['undo_redo_user_edit']);
        
        $user_admin = $this->input->post('user_admin');
        if ($user_admin != '0') {
            //Kenttä 'show' voi olla joko 0 tai 1
            $user_admin = '1';
        }
        
        $insert_data = array(
            'user_username'		=> $this->input->post('user_username'),
            'user_password'		=> $this->input->post('user_password'),
            'user_firstname'	=> $this->input->post('user_firstname'),
            'user_lastname'		=> $this->input->post('user_lastname'),
            'user_email'	    => $this->input->post('user_email'),
            'user_admin'	    => $user_admin
        );
        
        //Lisää uusi
        $this->User_model->insert($insert_data);
        
        //Tiedot undo/redo -toimintoa varten
        $user_id = $this->User_model->get_id_by_name($this->input->post('user_firstname'), $this->input->post('user_lastname'));
        
        $edit_info = array(
            'operation'	=> 'add',
            'key'		=> $user_id,
            'data_old'	=> null,
            'data_new'	=> $insert_data
        );
        
        //Lisäyksen tiedot muistiin
        $undo_redo_stack->execute($edit_info);
        
        /** SERIALIZE **/
        $_SESSION['undo_redo_user_edit'] = serialize($undo_redo_stack);
        
        $this->display($this->session->userdata('sort_by'),
            $this->session->userdata('sort_order'),
            $this->session->userdata('filter'));
    }
        
    public function delete($key_id, $filter = '')
    {
        //State variables of user_view
        $user_view_state_data = array(
            'filter'          => $filter
        );
        $this->session->set_userdata($user_view_state_data);
        
        /** UNSERIALIZE **/
        $undo_redo_stack = unserialize($_SESSION['undo_redo_user_edit']);
        
        //Haetaan poistettavan rivin tiedot
        $columns = array(
            'user_username',
            'user_password',
            'user_firstname',
            'user_lastname',
            'user_email',
            'user_admin'
         );
        $resultrow = $this->User_model->get_row_by_key($columns, $key_id);
        
        //Tiedot undo/redo -toimintoa varten
        $data_old = array(
            'user_username'		=> $resultrow['user_username'],
            'user_password'		=> $resultrow['user_password'],
            'user_firstname'	=> $resultrow['user_firstname'],
            'user_lastname'		=> $resultrow['user_lastname'],
            'user_email'		=> $resultrow['user_email'],
            'user_admin'  		=> $resultrow['user_admin']
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
        $_SESSION['undo_redo_user_edit'] = serialize($undo_redo_stack);
        
        //Poista tietue
        $this->User_model->delete($key_id);
        
        //Palataan päänäytölle siinä tilassa, kuin se oli ennen päivitystä
        $this->display($this->session->userdata('sort_by'),
            $this->session->userdata('sort_order'),
            $this->session->userdata('filter'));
    }

    public function undo() 
    {
        /** UNSERIALIZE **/
        $undo_redo_stack = unserialize($_SESSION['undo_redo_user_edit']);
        
        if ($undo_redo_stack->can_undo()) {
            $edit_info_data = $undo_redo_stack->undo();
            
            switch ($edit_info_data['operation']) {
                case "edit":
                    //Peru muutos
                    $update_data = array();
                    $update_data['user_username'] = $edit_info_data['data_old']['user_username'];
                    $update_data['user_firstname'] = $edit_info_data['data_old']['user_firstname'];
                    $update_data['user_lastname'] = $edit_info_data['data_old']['user_lastname'];
                    $update_data['user_email'] = $edit_info_data['data_old']['user_email'];
                    $update_data['user_admin'] = $edit_info_data['data_old']['user_admin'];
                    if (isset($edit_info_data['data_new']['user_password'])) {
                        $update_data['user_password'] = $edit_info_data['data_old']['user_password'];
                    }
                    $this->User_model->update($update_data, $edit_info_data['key']);
                    break;
  
                case "add":
                    //Poista lisätty rivi
                    $user_id = $this->User_model->get_id_by_name($edit_info_data['data_new']['user_firstname'], $edit_info_data['data_new']['user_lastname']);
                    $this->User_model->delete($user_id);
                    break;
                    
                case "delete":
                    //Lisää poistettu rivi
                    $insert_data = array(
					  'user_username'	=> $edit_info_data['data_old']['user_username'],
					  'user_password' 	=> $edit_info_data['data_old']['user_password'],
                      'user_firstname' 	=> $edit_info_data['data_old']['user_firstname'],
                      'user_lastname' 	=> $edit_info_data['data_old']['user_lastname'],
                      'user_email' 		=> $edit_info_data['data_old']['user_email'],
                      'user_admin' 		=> $edit_info_data['data_old']['user_admin']
                    );
                    $this->User_model->insert($insert_data);
                    break;
                    
                default:
                    break;
            } // switch
            
         } else {
            $this->session->set_flashdata("error", "Can't undo.");
        }
        
        /** SERIALIZE **/
        $_SESSION['undo_redo_user_edit'] = serialize($undo_redo_stack);
       
        //Palataan päänäytölle siinä tilassa, kuin se oli ennen päivitystä
        $this->display($this->session->userdata('sort_by'),
            $this->session->userdata('sort_order'),
            $this->session->userdata('filter'));
    }

    public function redo()
    {
        /** UNSERIALIZE **/
        $undo_redo_stack = unserialize($_SESSION['undo_redo_user_edit']);
        
        if ($undo_redo_stack->can_redo()) {
            $edit_info_data = $undo_redo_stack->redo();
            
            switch ($edit_info_data['operation']) {
                case "edit":
                    //Palauta muutos
                    $update_data = array();
                    $update_data['user_username'] = $edit_info_data['data_new']['user_username'];
                    $update_data['user_firstname'] = $edit_info_data['data_new']['user_firstname'];
                    $update_data['user_lastname'] = $edit_info_data['data_new']['user_lastname'];
                    $update_data['user_email'] = $edit_info_data['data_new']['user_email'];
                    $update_data['user_admin'] = $edit_info_data['data_new']['user_admin'];
                    if (isset($edit_info_data['data_new']['user_password'])) {
                        $update_data['user_password'] = $edit_info_data['data_new']['user_password'];
                    }
                    $this->User_model->update($update_data, $edit_info_data['key']);
                    break;
                    
                case "add":
                    $insert_data = array(
			 		   'user_username'	=> $edit_info_data['data_new']['user_username'],
					   'user_password' 	=> $edit_info_data['data_new']['user_password'],
                       'user_firstname' => $edit_info_data['data_new']['user_firstname'],
                       'user_lastname' 	=> $edit_info_data['data_new']['user_lastname'],
                       'user_email' 	=> $edit_info_data['data_new']['user_email'],
                       'user_admin' 	=> $edit_info_data['data_new']['user_admin']
                    );
                    $this->User_model->insert($insert_data);
                    break;
                    
                case "delete":
                    $user_id = $this->User_model->get_id_by_name($edit_info_data['data_old']['user_firstname'], $edit_info_data['data_old']['user_lastname']);
                    $this->User_model->delete($user_id);
                    break;
                    
                default:
                    break;
            } // switch
        } else {
            $this->session->set_flashdata("error", "Can't redo.");
        }
        
        /** SERIALIZE **/
        $_SESSION['undo_redo_user_edit'] = serialize($undo_redo_stack);
        
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
