<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Settings_controller extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        
        // Load the maintenance model to make it available
        // to *all* of the controller's actions
        $this->load->model('Settings_model');
        $this->load->model('UndoRedoStack');
    }
    
    public function display($sort_by = 'setting_order_id', $sort_order = 'asc') 
    {
        //State variables for maintenance_view
        if (($sort_by != $this->session->userdata('sort_by')) ||
            ($sort_order != $this->session->userdata('sort_order'))) {
                
                //Jos jokin tilamuuttuja muuttuu, poistetaan virheteksti näkyvistä
                if(isset($_SESSION['error'])){
                    unset($_SESSION['error']);
                }
            }
  
        //Tallenna näytön uusi tila
        $territory_view_state_data = array(
            'sort_by'         => $sort_by,
            'sort_order'      => $sort_order
        );
        $this->session->set_userdata($territory_view_state_data);
        
        $data['admin'] = $this->session->userdata('admin');
        
        //Hakuparametrit näytölle
        $data['display_fields'] = array(
            'setting_id'	     => 'id',
            'setting_order_id'	 => 'numero',
            'setting_input_type' => 'tyyppi',
            'setting_input_id'	 => 'piilokenttäid',
            'setting_desc'   	 => 'selite',
            'setting_value'	     => 'arvo',
            'setting_admin'	     => 'admin'
        );
        
        //Hakuparametrit kantaan
        $data['database_fields'] = array(
            'setting_id'	     => 'id',
            'setting_order_id'	 => 'numero',
            'setting_input_type' => 'tyyppi',
            'setting_input_id'	 => 'piilokenttäid',
            'setting_desc'	     => 'selite',
            'setting_value'	     => 'arvo',
            'setting_admin'	     => 'admin'
        );

        $data['table_not_found'] = "";
        //Hae tiedot
        if ($this->Settings_model->tableExists('settings')) {
            $results = $this->Settings_model->search($data['database_fields'], $sort_by, $sort_order);
        } else {
            $data['table_not_found'] = "Taulu 'settings' ei ole käytössä. Päivitykset eivät jää muistiin pysyvästi.";
            $results = $this->Settings_model->get_settings_offline();
        }
        
        //Tiedot näytölle sopiviksi
        $data['settings'] = $this->create_displayrows($results);
        
        //Asetukset session-muuttujiin
        $this->Settings_model->set_settings($results);
        
        //Parameters back to view page
        $data['sort_by'] = $sort_by;
        $data['sort_order'] = $sort_order;
        
        //Hae optiot
        //Aluekoodin editystapa -valitsin
        $terrCodePresentationOptions = array(
            'X999'  => 'X999',
            'I/999' => 'I/999',
            '999'   => '999',
        );
        $data['terrCodePresentationOptions'] = $terrCodePresentationOptions;
        
        //Nimen esitysmuoto-valitsin
        $namePresentationOptions = array(
            '0'     => 'Etunimi Sukunimi',
            '1'     => 'Sukunimi, Etunimi'
        );
        $data['namePresentationOptions'] = $namePresentationOptions;
        
        //Tapahtumamerkintöjen järjestys -valitsin
        $eventOrderOptions = array(
            'ASC'   => 'Nouseva',
            'DESC'  => 'Laskeva'
        );
        $data['eventOrderOptions'] = $eventOrderOptions;
        
        //Tapahtumamerkinnät ajalta korkeintaan -valitsin
        $archiveYearsOptions = array(
            '5'     => '5 vuotta',
            '6'     => '6 vuotta',
            '7'     => '7 vuotta',
            '8'     => '8 vuotta',
            '9'     => '9 vuotta',
            '10'    => '10 vuotta',
            '11'    => '11 vuotta',
            '12'    => '12 vuotta',
            '13'    => '13 vuotta',
            '14'    => '14 vuotta',
            '15'    => '15 vuotta',
            '16'    => '16 vuotta',
            '17'    => '17 vuotta',
            '18'    => '18 vuotta',
            '19'    => '19 vuotta',
            '20'    => '20 vuotta'
         );
        $data['archiveYearsOptions'] = $archiveYearsOptions;
        
        //Liikealueiden näyttäminen: -valitsin
        $btSwitchOptions = array(
            '0'     => 'Ei näytetä',
            '1'     => 'Näytetään'
        );
        $data['btSwitchOptions'] = $btSwitchOptions;
        
        //Tapahtumamerkintöjen tallennustapa -valitsin
        $eventSaveSwitchOptions = array(
            '0'     => 'Vain lainaukset ja palautukset',
            '1'     => 'Kaikki merkitsemiset'
        );
        $data['eventSaveSwitchOptions'] = $eventSaveSwitchOptions;
        
        
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
            if (!isset($_SESSION['undo_redo_setting_edit'])) {
                /** Initialize **/
                $undo_redo_stack = new UndoRedoStack();
            } else {
                /** UNSERIALIZE **/
                $undo_redo_stack = unserialize($_SESSION['undo_redo_setting_edit']);
            }
        }
        
        $data['can_undo'] = $undo_redo_stack->can_undo();
        $data['can_redo'] = $undo_redo_stack->can_redo();
        
        /** SERIALIZE **/
        $_SESSION['undo_redo_setting_edit'] = serialize($undo_redo_stack);
        
        $this->load->view('settings_view', $data);
    }
    
    private function create_displayrows($results)
    {
        $r = array();
        foreach ($results['rows'] as $fetched_row) {
            
            $resultrow = new stdClass;
            foreach ($fetched_row as $key=>$value) {
                switch ($key) {
                    case "setting_id":
                    case "setting_order_id":
                    case "setting_input_type":
                    case "setting_input_id":
                    case "setting_desc":
                    case "setting_value":
                    case "setting_admin":
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
        $keys = $this->input->post('setting_id_old');
        $field_settings = $this->input->post('setting_desc_old');
        
        $field_setting_values = $this->input->post('setting_value');
        $field_old_setting_values = $this->input->post('setting_value_old');
        
//         echo "Keys: ";
//         print_r($keys);
//         echo "<br>Old values: ";
//         print_r($field_old_setting_values);
//         echo "<br>New values: ";
//         print_r($field_setting_values);
        
        /** UNSERIALIZE **/
        $undo_redo_stack = unserialize($_SESSION['undo_redo_setting_edit']);
        
        for ($i = 0; $i < sizeof($field_settings); $i++) {
            if (($field_setting_values[$i] != $field_old_setting_values[$i])) {
                    $update_data = array(
                        'setting_value'	=> $field_setting_values[$i]
                    );
                    $this->Settings_model->update($update_data, $keys[$i]);
                    
                    //Tiedot undo/redo -toimintoa varten
                    $setting_data_old = array(
                        'setting_value'	=> $field_old_setting_values[$i]
                    );
                    
                    $setting_data_new = array(
                        'setting_value'	=> $field_setting_values[$i]
                    );
                    
                    //echo "i: ". $i;
                    //echo " vanha: ". $field_old_setting_values[$i];
                    //echo " uusi: ". $field_setting_values[$i];
                    
                    $setting_edit_info = array(
                        'operation'	=> 'edit',
                        'key'		=> $keys[$i],
                        'data_old'	=> $setting_data_old,
                        'data_new'	=> $setting_data_new
                    );
                    //Päivityksen tiedot muistiin
                    $undo_redo_stack->execute($setting_edit_info);
                    $_SESSION['undo_redo_setting_edit'] = serialize($undo_redo_stack);
                }
        }
        
        /** SERIALIZE **/
        $_SESSION['undo_redo_setting_edit'] = serialize($undo_redo_stack);
        
        //Palataan päänäytölle siinä tilassa, kuin se oli ennen päivitystä
        $this->display($this->session->userdata('sort_by'),
            $this->session->userdata('sort_order'));
        
        return ;
    }
        
    public function check_update()
    {
        $action = $this->input->post('action');
        switch ($action) {
            case "Päivitä":
            case "Update":
                $this->update_rows();
                break;
                
            case "Undo":
                $this->undo();
                break;

            case "Redo":
                $this->redo();
                break;
                
            case "Paluu":
            case "Return":
                //Palataan päänäytölle
                $main_url = 'Location: ' . base_url("index.php/Territory_controller/display");
                header($main_url);
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
                    $this->session->userdata('sort_order'));
                break;
        } // switch
        
       return;
    }

    public function undo() 
    {
        /** UNSERIALIZE **/
        $undo_redo_stack = unserialize($_SESSION['undo_redo_setting_edit']);
        
        if ($undo_redo_stack->can_undo()) {
            $edit_info_data = $undo_redo_stack->undo();
            
            switch ($edit_info_data['operation']) {
                case "edit":
                    //Peru muutos
                    $update_data = array(
                      'setting_value' => $edit_info_data['data_old']['setting_value']
                    );
                    $this->Settings_model->update($update_data, $edit_info_data['key']);
                    break;
                    
                 default:
                    break;
            } // switch
            
         } else {
            $this->session->set_flashdata("error", "Can't undo.");
        }
        
        /** SERIALIZE **/
        $_SESSION['undo_redo_setting_edit'] = serialize($undo_redo_stack);
       
        //Palataan päänäytölle siinä tilassa, kuin se oli ennen päivitystä
        $this->display($this->session->userdata('sort_by'),
            $this->session->userdata('sort_order'));
    }

    public function redo()
    {
        /** UNSERIALIZE **/
        $undo_redo_stack = unserialize($_SESSION['undo_redo_setting_edit']);
        
        if ($undo_redo_stack->can_redo()) {
            $edit_info_data = $undo_redo_stack->redo();
            
            switch ($edit_info_data['operation']) {
                case "edit":
                    //Palauta muutos
                    $update_data = array(
                       'setting_value' => $edit_info_data['data_new']['setting_value']
                    );
                    $this->Settings_model->update($update_data, $edit_info_data['key']);
                    break;
                    
                 default:
                    break;
            } // switch
        } else {
            $this->session->set_flashdata("error", "Can't redo.");
        }
        
        /** SERIALIZE **/
        $_SESSION['undo_redo_setting_edit'] = serialize($undo_redo_stack);
        
        //Palataan päänäytölle siinä tilassa, kuin se oli ennen päivitystä
        $this->display($this->session->userdata('sort_by'),
            $this->session->userdata('sort_order'));
    }
    
    public function index()
    {
        $this->display();
    }
    
}
