<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Settings_model extends CI_Model
{
    function __construct() {
        parent::__construct();
    }
    
    public function search($fields, $sort_by, $sort_order)
    {
        $sort_order = ($sort_order == 'desc') ? 'desc' : 'asc';
        
        $sort_columns = array();
        foreach ($fields as $field_name => $field_display) {
            $sort_columns[] = $field_name;
        }
        $sort_by = (in_array($sort_by, $sort_columns)) ? $sort_by : 'setting_order_id';
        
        $fetch_columns = array();
        foreach ($fields as $field_name => $field_display) {
            $fetch_columns[] = $field_name;
        }
        
        // Results query
        $query = $this->db->select($fetch_columns)
        ->from('settings');
        
        //Järjestys
        switch ($sort_by) {
            case "setting_order_id":
            case "setting_admin":
            case "setting_input_type":
            case "setting_input_id":
                $query = $this->db->order_by($sort_by, $sort_order);
                break;
                
            case "setting_desc":
            case "setting_value":
                //$query = $this->db->order_by("setting_admin", "DESC");
                $query = $this->db->order_by("setting_order_id", $sort_order);
                break;
                
            default:
                $query = $this->db->order_by("setting_order_id", "ASC");
                break;
        } // switch
        
        $ret['rows'] = $query->get()->result();
        
        return $ret;
    }

    public function get_settings_offline()
    {
        $settings_rows = array();
        
        //Seurakunnan nimi:
        $setting_row = new stdClass;
        $setting_row->setting_id = '1';
        $setting_row->setting_order_id = '1';
        $setting_row->setting_input_type = 'adminreadonly';
        $setting_row->setting_input_id = 'congregationName';
        $setting_row->setting_desc = 'Seurakunnan nimi:';
        $setting_row->setting_value = $this->session->userdata('congregationName');
        $setting_row->setting_admin = '0';
        $settings_rows[] = $setting_row;
        
        //Seurakunnan numero:
        $setting_row = new stdClass;
        $setting_row->setting_id = '2';
        $setting_row->setting_order_id = '2';
        $setting_row->setting_input_type = 'adminreadonly';
        $setting_row->setting_input_id = 'congregationNumber';
        $setting_row->setting_desc = 'Seurakunnan numero:';
        $setting_row->setting_value = $this->session->userdata('congregationNumber');
        $setting_row->setting_admin = '0';
        $settings_rows[] = $setting_row;
        
        //Kirjautuminen käytössä:
        $setting_row = new stdClass;
        $setting_row->setting_id = '3';
        $setting_row->setting_order_id = '3';
        $setting_row->setting_input_type = 'checkbox';
        $setting_row->setting_input_id = 'useSignIn';
        $setting_row->setting_desc = 'Kirjautuminen käytössä:';
        $setting_row->setting_value = $this->session->userdata('useSignIn');
        $setting_row->setting_admin = '0';
        $settings_rows[] = $setting_row;
        
        //Nimen esitysmuoto:
        $setting_row = new stdClass;
        $setting_row->setting_id = '4';
        $setting_row->setting_order_id = '11';
        $setting_row->setting_input_type = 'dropbox';
        $setting_row->setting_input_id = 'namePresentation';
        $setting_row->setting_desc = 'Nimen esitysmuoto:';
        $setting_row->setting_value = $this->session->userdata('namePresentation');
        $setting_row->setting_admin = '0';
        $settings_rows[] = $setting_row;
        
        //Tapahtumamerkintöjen järjestys:
        $setting_row = new stdClass;
        $setting_row->setting_id = '5';
        $setting_row->setting_order_id = '12';
        $setting_row->setting_input_type = 'dropbox';
        $setting_row->setting_input_id = 'eventOrder';
        $setting_row->setting_desc = 'Tapahtumamerkintöjen järjestys:';
        $setting_row->setting_value = $this->session->userdata('eventOrder');
        $setting_row->setting_admin = '0';
        $settings_rows[] = $setting_row;
        
        //Tapahtumamerkinnät ajalta korkeintaan:
        $setting_row = new stdClass;
        $setting_row->setting_id = '6';
        $setting_row->setting_order_id = '13';
        $setting_row->setting_input_type = 'dropbox';
        $setting_row->setting_input_id = 'archiveYears';
        $setting_row->setting_desc = 'Tapahtumamerkinnät ajalta korkeintaan:';
        $setting_row->setting_value = $this->session->userdata('archiveYears');
        $setting_row->setting_admin = '0';
        $settings_rows[] = $setting_row;
        
        //Liikealueiden näyttäminen:
        $setting_row = new stdClass;
        $setting_row->setting_id = '7';
        $setting_row->setting_order_id = '14';
        $setting_row->setting_input_type = 'dropbox';
        $setting_row->setting_input_id = 'btSwitch';
        $setting_row->setting_desc = 'Liikealueiden näyttäminen:';
        $setting_row->setting_value = $this->session->userdata('btSwitch');
        $setting_row->setting_admin = '0';
        $settings_rows[] = $setting_row;
        
        //Tapahtumamerkintöjen tallennustapa:
        $setting_row = new stdClass;
        $setting_row->setting_id = '8';
        $setting_row->setting_order_id = '15';
        $setting_row->setting_input_type = 'dropbox';
        $setting_row->setting_input_id = 'eventSaveSwitch';
        $setting_row->setting_desc = 'Tapahtumamerkintöjen tallennustapa:';
        $setting_row->setting_value = $this->session->userdata('eventSaveSwitch');
        $setting_row->setting_admin = '0';
        $settings_rows[] = $setting_row;
        
        //Kierrosviikko alkaa:
        $setting_row = new stdClass;
        $setting_row->setting_id = '9';
        $setting_row->setting_order_id = '16';
        $setting_row->setting_input_type = 'date';
        $setting_row->setting_input_id = 'circuitWeekStart';
        $setting_row->setting_desc = 'Kierrosviikko alkaa:';
        $setting_row->setting_value = $this->session->userdata('circuitWeekStart');
        $setting_row->setting_admin = '0';
        $settings_rows[] = $setting_row;
        
        //Kierrosviikko päättyy:
        $setting_row = new stdClass;
        $setting_row->setting_id = '10';
        $setting_row->setting_order_id = '17';
        $setting_row->setting_input_type = 'datereadonly';
        $setting_row->setting_input_id = 'circuitWeekEnd';
        $setting_row->setting_desc = 'Kierrosviikko päättyy:';
        $setting_row->setting_value = $this->session->userdata('circuitWeekEnd');
        $setting_row->setting_admin = '0';
        $settings_rows[] = $setting_row;
        
        //Aluekoodin editystapa:
        $setting_row = new stdClass;
        $setting_row->setting_id = '11';
        $setting_row->setting_order_id = '20';
        $setting_row->setting_input_type = 'dropbox';
        $setting_row->setting_input_id = 'terrCodePresentation';
        $setting_row->setting_desc = 'Aluekoodin editystapa:';
        $setting_row->setting_value = $this->session->userdata('terrCodePresentation');
        $setting_row->setting_admin = '1';
        $settings_rows[] = $setting_row;
        
        //Alue_detail-taulu käytössä:
        $setting_row = new stdClass;
        $setting_row->setting_id = '12';
        $setting_row->setting_order_id = '21';
        $setting_row->setting_input_type = 'checkbox';
        $setting_row->setting_input_id = 'useTerritoryDetaiTable';
        $setting_row->setting_desc = 'Alue_detail-taulu käytössä:';
        $setting_row->setting_value = $this->session->userdata('useTerritoryDetaiTable');
        $setting_row->setting_admin = '1';
        $settings_rows[] = $setting_row;
        
        $ret['rows'] = $settings_rows;
         
        return $ret;
    }
    
    public function set_settings($results)
    {
        $settings_data = array();
        foreach ($results['rows'] as $fetched_row) {
            
            $resultrow = new stdClass;
            foreach ($fetched_row as $key=>$value) {
                switch ($key) {
                    case "setting_value":
                        $settings_data[$fetched_row->setting_input_id] = $value;
                        break;
                        
                    case "setting_input_id":
                        break;
                        
                    default:
                        break;
                } // switch
            } // foreach results row
        }

        //Muodostettu asetus session-muuttujiin
        $this->session->set_userdata($settings_data);
        return ;
    }
    
    
    public function update($data, $key) 
    {
        if (!$this->tableExists('settings')) {
            $setting_data = array(
                $key => $data['setting_value']
            );
            
            $this->update_offline($setting_data);
            return ;
        }
        $this->db->set($data);
        $this->db->where("setting_input_id", $key);
        $this->db->update("settings", $data);
    }
    
    function update_offline($settings) 
    {
        $this->session->set_userdata($settings);
        
        return ;
    }
    
    public function tableExists($table) 
    {
        $db_debug = $this->db->db_debug;
        $this->db->db_debug = false;
        
        try {
            $query_str = "SELECT 1 FROM " . $table . " LIMIT 1";
            $result = $this->db->query($query_str);
            
            if (!$result)
            {
                throw new Exception('error in query');
                return false;
            }
            
            $this->db->db_debug = $db_debug;
            return TRUE;
            
            
        } catch (Exception $e) {
            //echo "Error: " . $e->getMessage();
            $this->db->db_debug = $db_debug;
            return FALSE;
        }
    }
    
    public function checkInitializeSettings()
    {
        //Jos käyttäjätaulu on poissa, älä käytä kirjautumista
        if (!$this->tableExists('users')) {
            $update_data = array(
                'setting_value'	=> 0
            );
            $this->update($update_data, "useSignIn");
            
            if ($this->Settings_model->tableExists('settings')) {
                //Nollaa asetukset vain, jos ne ovat kannassa
                $this->session->unset_userdata('initialized');
            }
        }
        
        //Jos asetuksia ei ole alustettu, haetaan asetukset
        if (empty($this->session->userdata('initialized'))) {
            
            //Hakuparametrit kantaan
            $data['database_fields'] = array(
                'setting_input_id'	 => 'tunniste',
                'setting_value'	     => 'arvo'
            );
            $sort_by = 'setting_order_id';
            $sort_order = 'asc';
            //Hae tiedot
            if ($this->tableExists('settings')) {
                 
                $results = $this->search($data['database_fields'], $sort_by, $sort_order);
                
                //Asetukset session-muuttujiin
                $this->set_settings($results);
            } else {
                $results = $this->default_settings();
            }
            
            //Merkitään asetukset alustetuksi
            $session_initialized = array(
                'initialized'     => 'K'
            );
            $this->session->set_userdata($session_initialized);
        }
    }
    
    
    public function default_settings() 
    {
        //default settings
        $settings_data = array(
            'congregationName' => 'Kankaanpää',
            'congregationNumber' => '38703',
            'useSignIn' => '0', //Kirjautuminen ei käytössä
            'terrCodePresentation' => 'X999', 
            'useTerritoryDetaiTable' => '0', //Alue_detail-taulu ei käytössä
            'namePresentation'  => '1',  //0 = firstname lsatname, 1 = lastmame, firstname; (default)
            'eventOrder' => 'DESC',
            'archiveYears' => '12',
            'btSwitch' => '0',  //Liikealueet: 0 = ei näytetä (default), 1 = näytetään
            'eventSaveSwitch' => '0', //Vain lainaukset ja palautukset
            'circuitWeekStart' => '8.12.2020',
            'circuitWeekEnd' => '13.12.2020'
        );
        $this->session->set_userdata($settings_data);
    }
}