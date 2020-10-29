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
        $this->load->model('Event_model');
        $this->load->model('Maintenance_model');
        $this->load->model('Person_model');
        $this->load->model('Settings_model');
        $this->load->model('Log_model');
        $this->load->model('UndoRedoStack');
    }
    
    /**
     * Näyttää aluetiedot. Hakee tiedot kannasta ja välittää ne näytölle. Pääohjelma
     *
     * @param	string sort_by    lajitteluavain Oletus: 'alue_code'
     * @param	string sort_order lajittelujärjestys Oletus: 'asc'
     * @param	string chkbox_sel Rajaus alueen sijainnin mukaan
     *          Arvot:  "0" Näytä kaikki (Oletus)
     *                  "1" Vain seurakunnassa
     *                  "2" Vain lainassa
     * @param	string date_sel   Rajaus alueen käyntipäivän mukaan
     *          Arvot:  "0" Näytä kaikki (Oletus)
     *                  "1" Vain yli 12 kk käymättä
     *                  "2" Vain yli 4 kk käymättä
     *                  "3" Vain yli 6 kk käymättä
     * @param	string code_sel   Rajaus alueen koodin mukaan
     *          Arvot:  " " Näytä kaikki (Oletus)
     *                  kirjain "A", "B" ... aluekoodi
     * @param	string filter   Rajaussuodatin
     *          " " Näytä kaikki (Oletus)
     * 
     * @return	-
     */
    public function display($sort_by = 'alue_code', $sort_order = 'asc', $chkbox_sel = '0', $date_sel = '0', $code_sel = '0', $filter = '') 
    {
        //Jos parametreja ei ole annettu, älä käytä kv-viikon alkupäivää rajauksessa
        $numargs = func_num_args();
        if ($numargs == 0) {
            $limit_date_sw = "0";
            
            //Poistetaan virheteksti näkyvistä
            if(isset($_SESSION['error'])){
                unset($_SESSION['error']);
            }
        }
 
        //Näytetäänkö liikeakueet?
        $bt_switch = $this->session->userdata('btSwitch');
        
        $limit_date_sw = $this->session->userdata('limit_date_sw');
        
        //State variables for territory_view
        $territory_view_state = array(
            'sort_by'         => $sort_by,
            'sort_order'      => $sort_order,
            'chkbox_sel'      => $chkbox_sel,
            'date_sel'        => $date_sel,
            'code_sel'        => $code_sel,
            'filter'          => $filter,
            'sivutunnus'      => "2",
            'limit_date_sw'   => $limit_date_sw
        );
        $this->session->set_userdata($territory_view_state);
        
        
        //Common control part
        $this->display_control($sort_by, $sort_order, $chkbox_sel, $date_sel, $code_sel, $bt_switch, $filter);
    }
    
    public function display_frontpage() 
    {
        //Jos asetuksia ei ole alustettu, haetaan asetukset
        if (empty($this->session->userdata('initialized'))) {
            $this->Settings_model->checkInitializeSettings();
        }
            
        $sort_by = 'alue_lastdate';
        $sort_order = 'asc';
        $chkbox_sel = '1'; //Aluepöydässä
        $date_sel = '2'; //yli 4 kk käymättä
        $code_sel = '0'; //Kaikki
        $bt_switch = '0';
        $filter = '';
        
        $territory_view_state = array(
            'sort_by'         => $sort_by,
            'sort_order'      => $sort_order,
            'chkbox_sel'      => $chkbox_sel,
            'date_sel'        => $date_sel,
            'code_sel'        => $code_sel,
            'filter'          => $filter,
            'sivutunnus'      => "1",
            'limit_date_sw'   => "0"
        );
        $this->session->set_userdata($territory_view_state);
    
        //Poistetaan virheteksti näkyvistä
        if(isset($_SESSION['error'])){
            unset($_SESSION['error']);
        }
        
        //Common control part
        $this->display_control($sort_by, $sort_order, $chkbox_sel, $date_sel, $code_sel, $bt_switch, $filter);
    }
    
    public function display_control($sort_by = 'alue_code', $sort_order = 'asc', $chkbox_sel = '0', $date_sel = '0', $code_sel = '0', $bt_switch = '0', $filter = '') 
    {
        //Hakuparametrit näytölle
        $data['display_fields'] = array(
            'alue_code'		=> 'numero',
            'alue_detail'	=> 'alue_nimi',
            'alue_location'	=> 'lisätieto',
            'lainassa'		=> 'lainassa',
            'alue_lastdate'	    => 'käyty',
            'event_last_date'	=> 'otettu',
            'name'	        => 'kenellä'
        );
        
        //Hakuparametrit kantaan
        $data['database_fields'] = array(
            'alue_code'		=> 'alue_koodi',
            'alue_detail'	=> 'alue_nimi',
            'alue_location'	=> 'alue_tietoja',
            'lainassa'		=> 'alue_lainassa',
            'alue_lastdate'	    => 'merkitty',
            'event_last_date'	=> 'otettu',
            'person_name'	=> 'etunimi',
            'person_lastname'	=> 'sukunimi'
        );
        
        //Korjaa ääkköset takaisin
        $filter = urldecode($filter);
        
        //Käytetäänkö rajauspäivämääränä kuluvaa päivää vai kierrosviikon alkupäivää
        $limit_date_sw = $this->session->userdata('limit_date_sw');

        //Hae tiedot
        $results = $this->Territory_model->search($data['database_fields'], $sort_by, $sort_order, $chkbox_sel, $date_sel, $code_sel, $bt_switch, $limit_date_sw);
        //Tiedot näytölle sopiviksi
        $data['alueet'] = $this->create_terr_displayrows($results);
        
        $data['num_results'] = $results['num_rows'];
        
        $data['pagination'] = "";
        
        //Parameters back to view page
        $data['sort_by'] = $sort_by;
        $data['sort_order'] = $sort_order;
        $data['chkbox_sel'] = $chkbox_sel;
        $data['date_sel'] = $date_sel;
        $data['code_sel'] = $code_sel;
        $data['filter'] = $filter;
        
        //Hae aluekoodit
        $tresults = $this->Event_model->get_terr_codes();
        $data['territory_codes'] = $tresults['rows'];
        
        $this->load->view('territory_view', $data);
    }
    
    public function display_mark_exhort() 
    {
        //Aseta näyttöparametrit
        $sort_by = 'name';
        $sort_order = 'asc';
        $chkbox_sel = '2';
        $date_sel = '5'; //Merkitty viimeksi 4 kk sitten
        $code_sel = '0';
        $bt_switch = '0';
        $filter = '';
        
        //State variables for territory_view
        $territory_view_state_data = array(
            'sort_by'         => $sort_by,
            'sort_order'      => $sort_order,
            'chkbox_sel'      => $chkbox_sel,
            'date_sel'        => $date_sel,
            'code_sel'        => $code_sel,
            'filter'          => $filter,
            'sivutunnus'      => "2",
            'limit_date_sw'   => "0"
        );
        $this->session->set_userdata($territory_view_state_data);
        
        $data['database_fields'] = array(
            'alue_code'		=> 'alue_koodi',
            'alue_detail'	=> 'alue_nimi',
            'alue_location'	=> 'alue_tietoja',
            'lainassa'		=> 'alue_lainassa',
            'event_last_date'	=> 'alue_muutospvm',
            'alue_lastdate'	=> 'event_lastdate',
            'person_name'	=> 'etunimi',
            'person_lastname'	=> 'sukunimi'
        );
        
        //Käytetäänkö rajauspäivämääränä kuluvaa päivää vai kierrosviikon alkupäivää
        $limit_date_sw = $this->session->userdata('limit_date_sw');
        
        //Hae tiedot
        $results = $this->Territory_model->search($data['database_fields'], $sort_by, $sort_order, $chkbox_sel, $date_sel, $code_sel, $bt_switch, $limit_date_sw);
        
        $data['terr_mark_list'] = $this->create_terr_mark_rows($results);
        $data['num_results'] = $results['num_rows'];

        $data['exhort'] = "MARK";
        
        $this->load->view('territory_mark_view', $data);
    }
    
    public function display_return_exhort()
    {
        //Aseta näyttöparametrit
        $sort_by = 'name';
        $sort_order = 'asc';
        $chkbox_sel = '2';
        $date_sel = '0';
        $code_sel = '0';
        $bt_switch = '0';
        $filter = '';
        
        //State variables for territory_view
        $territory_view_state_data = array(
            'sort_by'         => $sort_by,
            'sort_order'      => $sort_order,
            'chkbox_sel'      => $chkbox_sel,
            'date_sel'        => $date_sel,
            'code_sel'        => $code_sel,
            'filter'          => $filter,
            'sivutunnus'      => "2",
            'limit_date_sw'   => "0"
        );
        $this->session->set_userdata($territory_view_state_data);
        
        $data['database_fields'] = array(
            'alue_code'		=> 'alue_koodi',
            'alue_detail'	=> 'alue_nimi',
            'alue_location'	=> 'alue_tietoja',
            'lainassa'		=> 'alue_lainassa',
            'event_last_date'	=> 'alue_muutospvm',
            'person_name'	=> 'etunimi',
            'person_lastname'	=> 'sukunimi'
        );
        
        //Käytetäänkö rajauspäivämääränä kuluvaa päivää vai kierrosviikon alkupäivää
        $limit_date_sw = $this->session->userdata('limit_date_sw');
        
        //Hae tiedot
        $results = $this->Territory_model->search($data['database_fields'], $sort_by, $sort_order, $chkbox_sel, $date_sel, $code_sel, $bt_switch, $limit_date_sw);
        
        $data['terr_mark_list'] = $this->create_lent_rows($results);

        //Laske löytyneiden alueiden lkm
        $terr_count = 0;
        foreach ($data['terr_mark_list'] as $lender) {
            $terr_count = $terr_count + count($lender['territories']);
        }
        $data['num_results'] = $terr_count;
        $data['exhort'] = "RETURN";
        
        $this->load->view('territory_mark_view', $data);
    }
    
    public function display_co_report()
    {
        //Setting parameters to view page
        $data['report_date'] = $this->session->userdata('circuitWeekStart');
        
        $data['circuit_week_start'] = $this->session->userdata('circuitWeekStart');
        $data['circuit_week_end'] = $this->session->userdata('circuitWeekEnd');
        
        //Käytä raportin vertailupäivänä kierrosviikon alkupäivää
        $date_sw = '1';
        
        $limit_date_sw = $this->session->userdata('limit_date_sw');
        //Jos Kierrosviikon alusta -täppä on päällä, käytä kv-viikon alkupäivää vaikka se olisi jo mennyt
        if (!empty($limit_date_sw)) {
            $data['is_cw_coming'] = true;
        } else {
            $isCwComing = false;
            $data['is_cw_coming'] = $isCwComing;
            
            if (!$isCwComing) {
                //Jos kierrosviikko on mennyt, raporttipäiväksi kuluva päivä.
                //Vierailun ajankohtaa ei mäytetä.
                $today = date("Y-m-d");
                $unixDate = strtotime($today);
                $data['report_date'] = date('j.n.Y', $unixDate);
                $date_sw = '0'; //Vertailupäiväksi kuluva päivä
            }
        }
        
        //Total count query
        $data['territort_total_count'] = $this->Territory_model->getTerritoryCount('0', '0', '0', '1', $date_sw);
 
        //Hae tiedot alueita lainanneista seurakunnista
        $results = $this->Territory_model->get_borrowing_congs();
        $data['lainaukset'] = $results['rows'];
        
        $data['liikealue_count'] = $this->Territory_model->get_terr_group_count('L');
        
        //Vuosi käymättä lkm
        $data['vuosi_kaikki'] = $this->Territory_model->getTerritoryCount('0', '1', '0', '0', $date_sw);
        $data['vuosi_lainassa'] = $this->Territory_model->getTerritoryCount('2', '1', '0', '0', $date_sw);
        $data['vuosi_laatikossa'] = $this->Territory_model->getTerritoryCount('1', '1', '0', '0', $date_sw);
        
        $this->load->view('circuit_report_view', $data);
    }
    
    public function vertaaPvm() 
    {
        $isFuture = true;
      
        $today = date("Y-m-d");
        $cwStart = $this->session->userdata('circuitWeekEnd');
        
        $today_time = strtotime($today);
        $expire_time = strtotime($cwStart);
        
        if ($expire_time < $today_time) 
        { 
            $isFuture = false;
        }
        
        return $isFuture;
    }
    
    public function kierrosviikon_alusta($param_checked) 
    {
      
        //Vaihda kierrosviikon raja, jos parametrissa sama kuin session muuttujassa
        if ($this->session->userdata('limit_date_sw') == $param_checked) {
            if ($this->session->userdata('limit_date_sw') == '1') {
                $territory_view_state_data = array(
                    'limit_date_sw'          => '0'
                );
                $this->session->set_userdata($territory_view_state_data);
                
            } else if ($this->session->userdata('limit_date_sw') == '0') {
                $territory_view_state_data = array(
                    'limit_date_sw'          => '1'
                );
                $this->session->set_userdata($territory_view_state_data);
            }
        } else {
            $territory_view_state_data = array(
                'limit_date_sw'          => $param_checked
            );
            $this->session->set_userdata($territory_view_state_data);
        }
        
        $limit_date_sw = $this->session->userdata('limit_date_sw');
        
        //Jos pvm-rajaus on kv-viikon alku, hae oletuksena kaikki yli 12 kk käymättä olleet alueet
        if (!empty($limit_date_sw)) {
            $sort_by = 'name';
            $chkbox_sel = '0';
            $date_sel = '1';
            $code_sel = '0';
        } else {
            $sort_by = $this->session->userdata('sort_by');
            $chkbox_sel = $this->session->userdata('chkbox_sel');
            $date_sel = $this->session->userdata('date_sel');
            $code_sel = $this->session->userdata('code_sel');
        }
        
        //Palaa pääsivulle
        $this->display($sort_by,
            $this->session->userdata('sort_order'),
            $chkbox_sel,
            $date_sel,
            $code_sel,
            $this->session->userdata('filter'));
        
    }
    
    public function create_terr_displayrows($results) 
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
                        $mark_date = new DateTime($value);
                        $resultrow->alue_lastdate = $mark_date->format('j.n.Y');
                        break;
                        
                    case "person_name":
                        break;
                        
                    case "person_lastname":
                        if ($aluerivi->lainassa == "1") {
                            if (empty($aluerivi->person_name) && empty($aluerivi->person_lastname)) {
                                $resultrow->name = "Ei henkilöä";
                            } else {
                                if ($this->session->userdata('namePresentation') == "0") {
                                    //0 = firstname lsatname, 1 = lastmame, firstname; (default)
                                    $name_delim = ' ';
                                    $resultrow->name = $aluerivi->person_name . $name_delim . $value;
                                } else {
                                    $name_delim = ', ';
                                    $resultrow->name = $value . $name_delim . $aluerivi->person_name;
                                }
                            }
                         } else {
                            $resultrow->name = "";
                        }
                        break;
                    
                    case "event_last_date":
                        if ($aluerivi->lainassa == "1") {
                            $alue_eventdate = new DateTime($value);
                            $resultrow->event_last_date = $alue_eventdate->format('j.n.Y');
                        } else {
                            $resultrow->event_last_date = "";
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

    public function create_terr_mark_rows($results)
    {
        $terr_result = array();
        $publisher_mark = array();
        $territories = array();
        $territoty = array();
        $prev_name = "";
        
        foreach ($results['rows'] as $terr_row) {
            
            foreach ($terr_row as $key=>$value)
            {
                switch ($key) {
                    case "alue_code":
                        $territoty['alue_number'] = $value;
                        break;
                        
                    case "alue_detail":
                        break;
                        
                    case "alue_location":
                        //Alueen nimi = alue_detail + alue_location
                        if (empty($terr_row->alue_detail)) {
                            $territoty['alue_name'] = $value;
                        } else {
                            $territoty['alue_name'] = $terr_row->alue_detail . ", " . $value;
                        }
                        break;
                        
                    case "lainassa":
                        break;
                        
                    case "alue_lastdate":
                        $mark_date = new DateTime($value);
                        $territoty['alue_lastdate'] = $mark_date->format('j.n.Y');
                        break;
                        
                    case "event_last_date":
                        $event_last_date = new DateTime($value);
                        $territoty['event_last_date'] = $event_last_date->format('j.n.Y');
                        break;
                        
                    case "person_name":
                        break;
                        
                    case "person_lastname":
                        if ($this->session->userdata('namePresentation') == "0") {
                            //0 = firstname lsatname, 1 = lastmame, firstname; (default)
                            $name_delim = ' ';
                            $name = $terr_row->person_name . $name_delim . $value;
                        } else {
                            $name_delim = ', ';
                            $name =  $value . $name_delim . $terr_row->person_name;
                        }
                        //Nimi vaihtui?
                        if ($prev_name != $name) {
                            if (!empty($prev_name)) {
                                //Lisää nimi + alueet
                                $publisher_mark['name'] = $prev_name;
                                $publisher_mark['territories'] = $territories;
                                $terr_result[] = $publisher_mark;
                                $territories = array();
                                $publisher_mark = array();
                            }
                            $prev_name = $name;
                        }
                        $territories[] = $territoty;
                        $territoty = array();
                        break;

                    default:
                        break;
                } // switch
            } // foreach terr_row
        }

        //Lisää viimeinen nimi + alueet
        if (!empty($prev_name)) {
            $publisher_mark['name'] = $prev_name;
            $publisher_mark['territories'] = $territories;
            $terr_result[] = $publisher_mark;
        }
        return $terr_result;
    }
        
    public function create_lent_rows($results)
    {
        //arvo 1, jos on kirjattu myös merkkaukset events-tauluun, muuten 0.
        $event_save_switch = $this->session->userdata('eventSaveSwitch');
        
        $terr_result = array();
        $publisher_mark = array();
        $territories = array();
        $territoty = array();
        $prev_name = "";
        
        $limitDate = new DateTime();
        $limitDate->modify('-1 year');
         
        foreach ($results['rows'] as $terr_row) {
            
  
            
            if ($event_save_switch > 0) {
                $lending_alue_id = $this->Territory_model->get_terr_id($terr_row->alue_code);
                $lending_start_date1 = $this->Territory_model->get_lending_start_date($lending_alue_id);
                $lending_date = new DateTime($lending_start_date1);
            } else {
                $lending_start_date = $terr_row->event_last_date;
                $lending_date = new DateTime($lending_start_date);
            }

            //Tällä haettiin virheelliset
//             if ($lending_start_date != $lending_start_date1) {
//                 echo $terr_row->alue_code . " " . $lending_start_date . " " . $lending_start_date1 . " " . $terr_row->person_name . " " . $terr_row->person_lastname . "</br>";
//             }
            
            //poimitaan tietue vain, jos lending_date < 12 kk sitten
            if ($lending_date < $limitDate) {
                foreach ($terr_row as $key=>$value)
                {
                    switch ($key) {
                        case "alue_code":
                            $territoty['alue_number'] = $value;
                            break;
                            
                        case "alue_detail":
                            break;
                            
                        case "alue_location":
                            //Alueen nimi = alue_detail + alue_location
                            if (empty($terr_row->alue_detail)) {
                                $territoty['alue_name'] = $value;
                            } else {
                                $territoty['alue_name'] = $terr_row->alue_detail . ", " . $value;
                            }
                            break;
                            
                        case "lainassa":
                            break;
                            
                        case "event_last_date":
                            $territoty['event_last_date'] = $lending_date->format('j.n.Y');
                            break;
                            
                        case "person_name":
                            break;
                            
                        case "person_lastname":
                            if ($this->session->userdata('namePresentation') == "0") {
                                //0 = firstname lsatname, 1 = lastmame, firstname; (default)
                                $name_delim = ' ';
                                $name = $terr_row->person_name . $name_delim . $value;
                            } else {
                                $name_delim = ', ';
                                $name =  $value . $name_delim . $terr_row->person_name;
                            }
                            //Nimi vaihtui?
                            if ($prev_name != $name) {
                                if (!empty($prev_name)) {
                                    //Lisää nimi + alueet
                                    $publisher_mark['name'] = $prev_name;
                                    $publisher_mark['territories'] = $territories;
                                    $terr_result[] = $publisher_mark;
                                    $territories = array();
                                    $publisher_mark = array();
                                }
                                $prev_name = $name;
                            }
                            $territories[] = $territoty;
                            $territoty = array();
                            break;
                            
                        default:
                            break;
                    } // switch
                } // foreach terr_row
                
             } //jos lending_date < 12 kk sitten
        } // foreach results
        
        //Lisää viimeinen nimi + alueet
        if (!empty($prev_name)) {
            $publisher_mark['name'] = $prev_name;
            $publisher_mark['territories'] = $territories;
            $terr_result[] = $publisher_mark;
        }
        
        return $terr_result;
    }
    
    public function update ($terr_nbr, $filter = '') 
    {
        //State variables of territory_view
        $territory_view_state_data = array(
            'filter'          => $filter
        );
        $this->session->set_userdata($territory_view_state_data);
        
        $columns = array(
            'alue_code',
            'alue_detail',
            'alue_location',
            'lainassa',
            'alue_lastdate',
            'person_name',
            'person_lastname'
        );
        
        $territory_row = $this->get_latest_territory_row($columns, $terr_nbr);
        $data = array();
        $data['alue_code'] = $territory_row->alue_code;
        $data['alue_detail'] = $territory_row->alue_detail;
        $data['alue_location'] = $territory_row->alue_location;
        $data['lainassa'] = $territory_row->lainassa;
        $data['alue_lastdate'] = $territory_row->alue_lastdate;
        $data['name'] = $territory_row->name;
        
        $data['lenders'] = $this->get_lenders();
        
        $this->load->view('terr_mark', $data);
        
    }
    public function get_latest_territory_row($columns, $terr_nbr)
    {
        $alue_rivi = $this->Territory_model->get_alue_row($columns, $terr_nbr);
        
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
                    
                case "event_id":
                    $resultrow->event_id = $value;
                    break;
                
                case "alue_lastdate":
                    $mark_date = new DateTime($value);
                    $resultrow->alue_lastdate = $mark_date->format('j.n.Y');
                    break;
                    
                case "person_name":
                    break;
                    
                case "person_lastname":
                    if ($alue_rivi['lainassa'] == "1") {
                        if ($this->session->userdata('namePresentation') == "0") {
                            //0 = firstname lsatname, 1 = lastmame, firstname; (default)
                            $name_delim = ' ';
                            $resultrow->name = $alue_rivi['person_name'] . $name_delim . $value;
                        } else {
                            $name_delim = ', ';
                            $resultrow->name = $value . $name_delim . $alue_rivi['person_name'];
                        }
                        
                    } else {
                        $resultrow->name = "";
                    }
                    break;
                    
                default:
                    break;
            } // switch
        } // foreach aluerivi
        return $resultrow;
    }
    
    public function update_alue()
    {
        //Kirjataanko myös merkkaukset events-tauluun?
        $event_save_switch = $this->session->userdata('eventSaveSwitch');
        
        $alue_kayty = false;
        $alue_id = $this->Territory_model->get_terr_id($this->input->post('alue_code'));
        
        //Hae henkilön tunnus taulusta person
        $person_id_old = $this->get_person_id($this->input->post('jnimi_old'));
        $person_id_new = $this->get_person_id($this->input->post('djnimi'));
        
        $mark_date = new DateTime($this->input->post('dmerk'));
        $new_lastdate = $mark_date->format('Y-m-d');
        
        if ($this->session->userdata('lainassa_uusi') == "0") {  //palautus
            $event_data_new = array(
                'event_type' => "2",
                'event_date' => $new_lastdate,
                'event_user' => $person_id_old,
                'event_alue' => $alue_id
            );
            $this->modify_event($event_data_new,1);
            //Merkitään alue käydyksi, palautuneeksi
            $alue_kayty = true;
        } else { //lainaus
            if ($this->input->post('lainassa_old') == "1") { 
                //Jos lainaaja vaihtuu,
                if ($person_id_old != $person_id_new) {
                    //lisää palautumistapahtuma
                    $event_data_old = array(
                        'event_type' => "2",
                        'event_date' => $new_lastdate,
                        'event_user' => $person_id_old,
                        'event_alue' => $alue_id
                    );
                    $this->modify_event($event_data_old,1);
                    //Merkitään alue käydyksi
                    $alue_kayty = true;
                } else if ($event_save_switch > 0) {
                    //kirjataan myös merkkaukset,
                    $event_data_old = array(
                        'event_type' => "4",
                        'event_date' => $new_lastdate,
                        'event_user' => $person_id_old,
                        'event_alue' => $alue_id
                    );
                    $this->modify_event($event_data_old,1);
                    //Merkitään alue käydyksi
                    $alue_kayty = true;
                }
            }
            //Jos lainaaja vaihtuu
            if ($person_id_old != $person_id_new) {
                $event_data_new = array(
                    'event_type' => "1",
                    'event_date' => $new_lastdate,
                    'event_user' => $person_id_new,
                    'event_alue' => $alue_id
                );
                $this->modify_event($event_data_new,1);
            } else if ($event_save_switch > 0) {
                //kirjataan myös merkkaukset,
                $event_data_new = array(
                    'event_type' => "3",
                    'event_date' => $new_lastdate,
                    'event_user' => $person_id_new,
                    'event_alue' => $alue_id
                );
                $this->modify_event($event_data_new,1);
            }
        }  //Lainaus
               
        if ($alue_kayty) {
            $data = array(
                'lainassa' => $this->session->userdata('lainassa_uusi'),
                'alue_lastdate' => $new_lastdate
            );
        } else {
            $data = array(
                'lainassa' => $this->session->userdata('lainassa_uusi')
            );
        }
        
        $this->Territory_model->update($data, $this->input->post('alue_code'));
 
        //Palataan päänäytölle siinä tilassa, kuin se oli ennen päivitystä
        if ($this->session->userdata('sivutunnus') == 1) {
            $this->display_frontpage($this->session->userdata('sort_by'),
                $this->session->userdata('sort_order'),
                $this->session->userdata('chkbox_sel'),
                $this->session->userdata('date_sel'),
                $this->session->userdata('code_sel'),
                $this->session->userdata('filter'));
        } else {
            $this->display($this->session->userdata('sort_by'),
                $this->session->userdata('sort_order'),
                $this->session->userdata('chkbox_sel'),
                $this->session->userdata('date_sel'),
                $this->session->userdata('code_sel'),
                $this->session->userdata('filter'));
        }
        
    }
    
    public function modify_event($event_data, $operation) 
    {
        //Hae alueen viimeisin tapahtuma
        $columns_event = array(
            'event_id',
            'event_type',
            'event_date',
            'event_user',
            'event_alue'
        );
        $alue_id = $event_data['event_alue'];
        
        //toiminnon mukaan
        switch ($operation) {
            case "1": //Lisäys
            case "3": //Lisäys, undo
                $this->Event_model->insert($event_data);

                //Hae lisäty tapahtumarivi
                $results = $this->Event_model->get_latest_event_data($columns_event, $alue_id);
                $resultrow = $results['rows'][0];
   
                //Lisää lokitapahtuma
                $log_data = array(
                    'log_event_id' => $resultrow->event_id,
                    'log_event_type' => $resultrow->event_type,
                    'log_event_date' => $resultrow->event_date,
                    'log_event_person' => $resultrow->event_user,
                    'log_event_terr' => $alue_id,
                    'log_user_id' => $this->session->userdata('user_id'),
                    'log_operation_code' => $operation
                );
                $this->Log_model->insert($log_data);
                break;
                
            case "2": //Poisto
            case "4": //Poisto, redo
                //Hae tapahtumarivi ennen poistoa
                $results = $this->Event_model->get_latest_event_data($columns_event, $alue_id);
                $resultrow = $results['rows'][0];
                
                //Lisää lokitapahtuma ennen poistoa
                $log_data = array(
                    'log_event_id' => $resultrow->event_id,
                    'log_event_type' => $resultrow->event_type,
                    'log_event_date' => $resultrow->event_date,
                    'log_event_person' => $resultrow->event_user,
                    'log_event_terr' => $alue_id,
                    'log_user_id' => $this->session->userdata('user_id'),
                    'log_operation_code' => $operation
                );
                $this->Log_model->insert($log_data);
                
                $this->Event_model->delete($event_data['event_id']);
                break;
                
            default:
                break;
        } // switch

        
    }
    
    public function get_person_id($name) 
    {
        $person_id = 0;
        
        //Jos nimi ei ole tyhjä, haetaan id
        if (!empty($name)) {
            $nimet = preg_split("/[, ]+/", $name);

            if ($this->session->userdata('namePresentation') == "0") {
                //0 = firstname lsatname, 1 = lastmame, firstname; (default)
                $etunimi = $nimet[0];
                $sukunimi = $nimet[1];
            } else {
                $etunimi = $nimet[1];
                $sukunimi = $nimet[0];
            }
            
            //Onko nimi kannassa?
            $person_id = $this->Person_model->get_id_by_name($etunimi, $sukunimi);
            if ($person_id < 0) {
                //Ei, lisätään
                $insert_data = array(
                    'person_name' => $etunimi,
                    'person_lastname' => $sukunimi,
                    'person_group'	=> '5',
                    'person_leader'	=> '0',
                    'person_show'	=> '1'
                );
                
                //Lisää uusi
                $this->Person_model->insert($insert_data);
                $person_id = $this->Person_model->get_id_by_name($etunimi, $sukunimi);
            } else {
                //Tarkistetaan vielä, onko löytynyt lainaaja aktiivinen
                $columns = array(
                    'person_group'
                );
                
                $resultrow = $this->Person_model->get_row_by_key($columns, $person_id);
                if ($resultrow['person_group'] == 0) {
                    //Ellei ole, päivitetään aktiiviseksi
                    $data = array(
                        'person_group' => '5'
                    );
                    $this->Person_model->update($data, $person_id);
                }
            }
        }
        
        return $person_id;
    }
    
    public function get_lenders()
    {
        $person_fields = array(
            'person_name'	=> 'etunimi',
            'person_lastname'	=> 'sukunimi',
            'person_group'	=> 'ryhmä'
        );
        //Hae lainaajien nimet
        $person_results = $this->Person_model->search($person_fields, "person_lastname", "ASC","A");
        
        //Muokkaa haetut tiedot näytölle sopiviksi
        $lenders = $this->create_lender_rows($person_results);
    
        return $lenders;
    }
    
    public function create_lender_rows($results) 
    {
        $options = array();
        $options[' '] = ' ';
        
        foreach ($results['rows'] as $person_row) {
            //Poimitaan vain ahtiiviset lainaajat
            if ($person_row->person_group > 0) {
                $resultrow = array();
                foreach ($person_row as $key=>$value) {
                    switch ($key) {
                        case "person_name":
                            break;
                            
                        case "person_lastname":
                            if ($this->session->userdata('namePresentation') == "0") {
                                //0 = firstname lsatname, 1 = lastmame, firstname; (default)
                                $name_delim = ' ';
                                $resultrow['name'] = $person_row->person_name . $name_delim . $value;
                            } else {
                                $name_delim = ', ';
                                $resultrow['name'] = $value . $name_delim . $person_row->person_name;
                            }
                            $resultrow['show_name'] = $value . ' ' . $person_row->person_name;
                            break;
                            
                        default:
                            break;
                    } // switch
                } // foreach person_row
                $options[$resultrow['name']] = $resultrow['show_name'];
            } // end if aktiivinen
        }
        $options['uusinimi'] = 'uusinimi'; //Mahdollisuus lisätä uusi nimi
        
        return $options;
    }
    
    public function check_territory() 
    {
        $action = $this->input->post('action');
        switch ($action) {
            case "Päivitä":
                // set validation rules
                $rules = array(
                array('field' => 'djnimi',
                      'label' => 'Kenellä',
                      'rules' => 'callback_verify_alue')
                ) ;
                // check input data
                $this->form_validation->set_rules($rules);
                
                if ($this->form_validation->run() == false) {
                    $this->update($this->input->post('alue_code'), $this->session->userdata('filter'));
                } else {
                    $this->update_alue();
                }
                break;
                
            case "Historia":
                //Alusta tietorakenne undo/redo - toimintoa varten
                $undo_redo_stack = new UndoRedoStack();
                $_SESSION['undo_redo_stack'] = serialize($undo_redo_stack);

                //Poistetaan aikaisemmin näkynyt virheteksti
                if(isset($_SESSION['error'])){
                    unset($_SESSION['error']);
                }
                $this->territory_history($this->input->post('alue_code'));
                break;
                
            case "Paluu":
                //Palataan päänäytölle siinä tilassa, kuin se oli ennen päivitystä
                if ($this->session->userdata('sivutunnus') == 1) {
                    $this->display_frontpage($this->session->userdata('sort_by'),
                        $this->session->userdata('sort_order'),
                        $this->session->userdata('chkbox_sel'),
                        $this->session->userdata('date_sel'),
                        $this->session->userdata('code_sel'),
                        $this->session->userdata('filter'));
                } else {
                    $this->display($this->session->userdata('sort_by'),
                        $this->session->userdata('sort_order'),
                        $this->session->userdata('chkbox_sel'),
                        $this->session->userdata('date_sel'),
                        $this->session->userdata('code_sel'),
                        $this->session->userdata('filter'));
                }
                break;
            
            default:
                $msg = "Tunnistamaton toiminto.";
                $num_vars = count( explode( '###', http_build_query($_POST, '', '###') ) );
                $max_num_vars = ini_get('max_input_vars');
                if ($num_vars > $max_num_vars) {
                    $msg .= " Input-parametreja on enemmän kuin " . $max_num_vars;
                }
                $this->session->set_flashdata('error', $msg);
                
                $this->update($this->input->post('alue_code'), $this->session->userdata('filter'));
                break;
        } // switch
         
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
        $julistaja_vanha = $this->input->post('jnimi_old');
        
        $pvm_uusi = $this->input->post('dmerk');
        $pvm_vanha = $this->input->post('lastdate_old');
        
        if ($lainassa_vanha == '0' && $lainassa_uusi == '0') {
            $this->form_validation->set_message('verify_alue','väärä lainassa-koodi!');
            $this->session->set_flashdata('error', 'Palautat palautunutta korttia. Yritä uudelleen.');
            return false;
        } else if ($lainassa_uusi == '1' && empty($julistaja_uusi)) {
                $this->form_validation->set_message('verify_alue','Lainaajan nimi tyhjä!');
                $this->session->set_flashdata('error', 'Lainaajan nimi tyhjä. Yritä uudelleen.');
                return false;
        } else if ($lainassa_vanha == '1' && $lainassa_uusi == '1'
                   && $julistaja_vanha == $julistaja_uusi
                   && $pvm_vanha == $pvm_uusi) {
                $this->form_validation->set_message('verify_alue','Kortti on jo merkitty!');
                $this->session->set_flashdata('error', 'Et voi merkitä korttia samana päivänä samalle henkilölle uudelleen');
                return false;
        }

        //Poistetaan aikaisemmin näkynyt virheteksti
        if(isset($_SESSION['error'])){
            unset($_SESSION['error']);
        }
        return true;
    }
    
    public function territory_history($terr_nbr, $main_display="territory_view")
    {
        $columns = array(
            'alue_id',
            'alue_code'
        );
        
        $resultrow = $this->Maintenance_model->get_row_by_key($columns, $terr_nbr);
        
        $event_fields = array(
            'alue_code'		=> 'alue_koodi',
            'event_type'	=> 'event_tyyppi',
            'event_date'	=> 'event_date',
            'person_name'	=> 'etunimi',
            'person_lastname'	=> 'sukunimi'
        );
        //Hae alueen tapahtumat tunnuksella
        $event_results = $this->Event_model->search_event_data($event_fields, $resultrow['alue_id'],
            $this->session->userdata('archiveYears'),
            $this->session->userdata('eventOrder'));
        $events_alue = $this->Event_model->tabulate_alue_events($event_results, $this->session->userdata('eventOrder'));
        $events_data = array();
        $events_data[] = $events_alue;
        $data['terr_nbr'] = $terr_nbr;
        $data['main_display'] = $main_display;
        $data['event_data'] = $this->Event_model->tabulate($events_data);

        /** UNSERIALIZE **/
        $undo_redo_stack = unserialize($_SESSION['undo_redo_stack']);
        $data['can_undo'] = $undo_redo_stack->can_undo();
        $data['can_redo'] = $undo_redo_stack->can_redo();
        
        $this->load->view('terr_history', $data);
    }
    
    public function check_history($terr_nbr, $main_display="territory_view")
    {
        /** UNSERIALIZE **/
        $undo_redo_stack = unserialize($_SESSION['undo_redo_stack']);
        
        $action = $this->input->post('action');
        
        switch ($action) {
            case "Poista":
            case "Remove":
                $event_data = $this->remove_event($terr_nbr,2);
                 //Tapahtuman tiedot muistiin
                $undo_redo_stack->execute($event_data);
                $_SESSION['undo_redo_stack'] = serialize($undo_redo_stack);
                
                $this->territory_history($terr_nbr, $main_display);
                break;
                
            case "Undo":
                if ($undo_redo_stack->can_undo()) {
                    $event_data = $undo_redo_stack->undo();
                    $_SESSION['undo_redo_stack'] = serialize($undo_redo_stack);
                    $this->add_event($terr_nbr, $event_data);
                } else {
                    $this->session->set_flashdata("error", "Can't undo.");
                }
                $this->territory_history($terr_nbr, $main_display);
                break;
                
            case "Redo":
                if ($undo_redo_stack->can_redo()) {
                    $event_data = $undo_redo_stack->redo();
                    $_SESSION['undo_redo_stack'] = serialize($undo_redo_stack);
                    $event_data_deleted = $this->remove_event($terr_nbr,4);
                } else {
                    $this->session->set_flashdata("error", "Can't redo.");
                }
                $this->territory_history($terr_nbr, $main_display);
                break;
 
            case "Paluu":
            case "Return":
                //Palataan päänäytölle siinä tilassa, kuin se oli ennen päivitystä
                if ($main_display == "event_view") {
                    $code = $this->session->userdata('page_code');
                    $offset = $this->session->userdata('page_offset');
                    
                    $this->redirect_to_event_page($code, $offset);

                } else {
                    if ($this->session->userdata('sivutunnus') == 1) {
                        $this->display_frontpage($this->session->userdata('sort_by'),
                            $this->session->userdata('sort_order'),
                            $this->session->userdata('chkbox_sel'),
                            $this->session->userdata('date_sel'),
                            $this->session->userdata('code_sel'),
                            $this->session->userdata('filter'));
                    } else {
                        $this->display($this->session->userdata('sort_by'),
                            $this->session->userdata('sort_order'),
                            $this->session->userdata('chkbox_sel'),
                            $this->session->userdata('date_sel'),
                            $this->session->userdata('code_sel'),
                            $this->session->userdata('filter'));
                    }
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
                
                $this->territory_history($terr_nbr, $main_display);
                break;
        } // switch
        
        return ;
    }
    
    public function remove_event($terr_nbr, $operation)
    {
        //Hae alue_id
        $columns = array(
            'alue_id',
            'alue_lastdate'
        );
        
        $resultrow = $this->Maintenance_model->get_row_by_key($columns, $terr_nbr);
        $alue_id = $resultrow['alue_id'];
        $alue_lastdate = $resultrow['alue_lastdate'];
         
        //Hae alueen viimeisin tapahtuma
        $columns_event = array(
            'event_id',
            'event_type',
            'event_date',
            'event_user',
            'event_alue'
        );
        $results = $this->Event_model->get_latest_event_data($columns_event, $alue_id);
        
        $resultrow = $results['rows'][0];
        $event_data = array(
            'event_type' => $resultrow->event_type,
            'event_date' => $resultrow->event_date,
            'event_user' => $resultrow->event_user,
            'event_alue' => $resultrow->event_alue,
            'alue_lastdate' => $alue_lastdate
        );
        
        //Poista tapahtuma
        $event_data_new = array(
            'event_id' => $resultrow->event_id,
            'event_alue' => $resultrow->event_alue
        );
        $this->modify_event($event_data_new,$operation);
        
        //Poiston jälkeen haetaan viimeisin tapahtuma
        $results2 = $this->Event_model->get_latest_event_data($columns_event, $alue_id);
        if (count($results2['rows']) > 0) {
            //Jos löytyi
            $resultrow2 = $results2['rows'][0];
            if ($resultrow2->event_type == 2) {
                $data = array(
                    'lainassa' => '0',
                    'alue_lastdate' => $resultrow2->event_date
                );
                $this->Territory_model->update($data, $terr_nbr);
                
            } else {
                $data = array(
                    'lainassa' => '1',
                    'alue_lastdate' => $resultrow2->event_date
                );
                $this->Territory_model->update($data, $terr_nbr);
            }
        } else {
            $endDate = new DateTime('first day of january');
            $endDate->modify('-5 year');
            $data = array(
                'lainassa' => '0',
                'alue_lastdate' => $endDate->format('Y-m-d')
            );
            $this->Territory_model->update($data, $terr_nbr);
        }
 
        return $event_data;
    }

    public function add_event($terr_nbr, $event_history_data)
    {
        //Hae alue_id
        $alue_id = $this->Territory_model->get_terr_id($terr_nbr);
        
        //Hae alueen viimeisin tapahtuma
        $columns_event = array(
            'event_id',
            'event_type',
            'event_date',
            'event_user',
            'event_alue'
        );
        
        $event_data = array(
            'event_type' => $event_history_data['event_type'],
            'event_date' => $event_history_data['event_date'],
            'event_user' => $event_history_data['event_user'],
            'event_alue' => $event_history_data['event_alue']
        );
        
        //Lisää tapahtuma
        $this->modify_event($event_data,3);
        
        $alue_lastdate = $event_history_data['alue_lastdate'];
        
        //Lisäyksen jälkeen haetaan viimeisin tapahtuma
        $results2 = $this->Event_model->get_latest_event_data($columns_event, $alue_id);
        
        $resultrow2 = $results2['rows'][0];
        if ($resultrow2->event_type == 2) {
            $data = array(
                'lainassa' => '0',
                'alue_lastdate' => $alue_lastdate
            );
            $this->Territory_model->update($data, $terr_nbr);
            
        } else {
            $data = array(
                'lainassa' => '1',
                'alue_lastdate' => $alue_lastdate
            );
            $this->Territory_model->update($data, $terr_nbr);
        }
        return ;
    }
    
    public function redirect_to_event_page($code = 'A', $offset = 0)
    {
        $new_url = "Event_controller/display/" . $code . "/" . $offset;
        redirect($new_url);
    }
    
    public function index()
    {
        if ($this->Settings_model->tableExists('settings')) {
            //Nollaa asetukset vain, jos ne ovat kannassa
            $this->session->unset_userdata('initialized');
        }
        $this->Settings_model->checkInitializeSettings(); //Tsekataan tässä asetukset
        if (!empty($this->session->userdata('useSignIn'))) {
            $new_url = base_url("index.php/LoginController");
            header('Location: ' . $new_url);
        } else {
            $this->display_frontpage();
        }
    }
}
