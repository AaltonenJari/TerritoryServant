<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Log_controller extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        
        // Load the maintenance model to make it available
        // to *all* of the controller's actions
        $this->load->model('User_model');
        $this->load->model('Log_model');
     }
    
    public function display($sort_by = 'user_username', $sort_order = 'asc', $user_sel = 'Kaikki', $date_sel = '0', $filter = '') 
    {
        //Jos parametreja ei ole annettu, käytä oletuksena nykyistä käyttäjää
        $numargs = func_num_args();
        if ($numargs == 0) {
            $user_sel = $this->session->userdata('user_id');
        }
        
        //State variables for maintenance_view
        if (($sort_by != $this->session->userdata('sort_by')) ||
            ($sort_order != $this->session->userdata('sort_order')) ||
            ($user_sel != $this->session->userdata('user_sel')) ||
            ($date_sel != $this->session->userdata('date_sel')) ||
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
            'user_sel'        => $user_sel,
            'date_sel'        => $date_sel,
            'filter'          => $filter
        );
        $this->session->set_userdata($territory_view_state_data);
        
        //Hakuparametrit näytölle
        $data['display_fields'] = array(
            'user_username'      => 'käyttäjä',
            'log_id'		     => 'lokitunnus',
            'log_event_id'       => 'tapahtumatunnus',
            'log_timestamp'	     => 'aikaleima',
            'alue_code'	         => 'alue',
            'name'	             => 'kuka',
            'log_event_type'	 => 'tapahtuma',
            'log_event_date'     => 'koska',
            'log_operation_code' => 'toiminto'
        );
        
        //Hakuparametrit kantaan
        $data['database_fields'] = array(
            'user_username'      => 'käyttäjä',
            'log_id'		     => 'lokitunnus',
            'log_event_id'       => 'tapahtumatunnus',
            'log_event_person'   => 'personId',
            'log_event_terr'     => 'alueId',
            'log_timestamp'	     => 'aikaleima',
            'alue_code'	         => 'alue',
            'person_name'	     => 'etunimi',
            'person_lastname'	 => 'sukunimi',
            'log_event_type'	 => 'tapahtuma',
            'log_event_date'     => 'koska',
            'log_operation_code' => 'toiminto'
        );

        //Korjaa ääkköset takaisin
        $filter = urldecode($filter);
        
        //Hae tiedot
        $results = $this->Log_model->search($data['database_fields'], $sort_by, $sort_order, $user_sel, $date_sel);
        //Tiedot näytölle sopiviksi
        $data['log_rows'] = $this->create_displayrows($results);
        
        $data['num_results'] = $results['num_rows'];
        
        //Parameters back to view page
        $data['sort_by'] = $sort_by;
        $data['sort_order'] = $sort_order;
        $data['user_sel'] = $user_sel;
        $data['date_sel'] = $date_sel;
        $data['filter'] = $filter;
        
        //Aseta optiot
        //pvm rajaus -valitsin
        $logDateOptions = array(
            '0 = Kaikki'           => '0',
            '1 = alle 1/2 vuotta'  => '1',
            '2 = alle 1 vuosi'     => '2',
            '3 = alle 2 vuotta'    => '3',
        );
        $data['logDateOptions'] = $logDateOptions;
        
        //Hae käyttäjien käyttäjätunnukset
        $user_fields = array(
            'user_id',
            'user_username'
        );
        $user_fields = array(
            'user_id'			=> 'tunnus',
            'user_username'		=> 'käyttäjänimi'
        );
        
        $user_results = $this->User_model->search($user_fields, 'user_id', 'ASC');

        //Muokkaa haetut tiedot näytön optioiksi
        $data['userOptions'] = $this->create_user_options($user_results);
    
        $data['saved_height'] = $this->session->userdata('table_height');
        $this->load->view('log_view', $data);
    }
    
    private function create_displayrows($results)
    {
        $tapahtumatyypit = array(
            '1'		=> 'Lainaus',
            '2'		=> 'Palautus',
            '3'		=> 'Merkkaus, lainaus',
            '4'		=> 'Merkkaus, käyty',
        );
        
        $operaatiot = array(
            '1'		=> 'Lisäys',
            '2'		=> 'Poisto',
            '3'		=> 'Lisäys, undo',
            '4'		=> 'Poisto, redo',
        );
        
        $r = array();
        foreach ($results['rows'] as $fetched_row) {
            
            $resultrow = new stdClass;
            foreach ($fetched_row as $key=>$value) {
                switch ($key) {
                    case "log_id":
                    case "user_username":
                    case "alue_code":
                        $resultrow->$key = $value;
                        break;
                        
                    case "log_timestamp":
                        $mark_date = new DateTime($value);
                        $resultrow->$key = $mark_date->format('j.n.Y h:i:s');
                        break;
                    
                    case "log_event_type":
                        if (!empty($value)) {
                            $resultrow->$key = $tapahtumatyypit[$value];
                        } else {
                            $resultrow->$key = "";
                        }
                        break;
                        
                    case "log_event_date":
                        $mark_date = new DateTime($value);
                        $resultrow->$key = $mark_date->format('j.n.Y');
                        break;
                        
                    case "person_name":
                        break;
                        
                    case "person_lastname":
                        if (empty($fetched_row->person_name) && empty($fetched_row->person_lastname)) {
                            $resultrow->name = "Ei henkilöä";
                        } else {
                            if ($this->session->userdata('namePresentation') == "0") {
                                //0 = firstname lsatname, 1 = lastmame, firstname; (default)
                                $name_delim = ' ';
                                $resultrow->name = $fetched_row->person_name . $name_delim . $value;
                            } else {
                                $name_delim = ', ';
                                $resultrow->name = $value . $name_delim . $fetched_row->person_name;
                            }
                        }
                        break;

                    case "log_operation_code":
                        $resultrow->$key = $operaatiot[$value];
                        break;
                    
                    default:
                        $resultrow->$key = $value;
                        break;
                } // switch
            } // foreach results row
            $r[] = $resultrow;
        }
        return $r;
    }

    private function create_user_options($results)
    {
        $userid = $this->session->userdata('user_id');
        $admin = $this->session->userdata('admin');
        
        $options = array();
        if ($admin) {
            $options['kaikki'] = 'Kaikki'; //Ei-kirjautunut käyttäjä
            $options['0 guest'] = '0'; //Ei-kirjautunut käyttäjä
        } else {
            if ($userid == '0') {
                $options['0 guest'] = '0'; //Ei-kirjautunut käyttäjä
            }
        }

        foreach ($results['rows'] as $user_row) {
            $option_row = array();
            foreach ($user_row as $key=>$value) {
                switch ($key) {
                    case "user_id":
                        break;
                        
                    case "user_username":
                        if ($admin) {
                            $options[$user_row->user_id . " " .$value] = $user_row->user_id;
                        } else {
                            if ($userid == $user_row->user_id) {
                                $options[$user_row->user_id . " " .$value] = $user_row->user_id;
                            }
                        }
                        break;
                        
                    default:
                        break;
                } // switch
            } // foreach person_row
        }
        return $options;
    }
    
}
