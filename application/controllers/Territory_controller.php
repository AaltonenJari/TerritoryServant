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
        //Näytetäänkö liikeakueet?
        $bt_switch = $this->session->userdata('bt_switch');
        
        //State variables for territory_view
        $territory_view_state_data = array(
            'sort_by'         => $sort_by,
            'sort_order'      => $sort_order,
            'chkbox_sel'      => $chkbox_sel,
            'date_sel'        => $date_sel,
            'code_sel'        => $code_sel,
            'filter'          => $filter,
            'sivutunnus'      => "2"
        );
        $this->session->set_userdata($territory_view_state_data);
        
        //Common control part
        $this->display_control($sort_by, $sort_order, $chkbox_sel, $date_sel, $code_sel, $bt_switch, $filter);
    }
    
    public function display_frontpage() 
    {
        $sort_by = 'mark_date';
        $sort_order = 'asc';
        $chkbox_sel = '1';
        $date_sel = '2';
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
            'sivutunnus'      => "1"
        );
        $this->session->set_userdata($territory_view_state_data);
    
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
            'mark_date'	    => 'käyty',
            'event_last_date'	=> 'otettu',
            'name'	        => 'kenellä'
        );
        
        //Hakuparametrit kantaan
        $data['database_fields'] = array(
            'alue_code'		=> 'alue_koodi',
            'alue_detail'	=> 'alue_nimi',
            'alue_location'	=> 'alue_tietoja',
            'lainassa'		=> 'alue_lainassa',
            'mark_date'	    => 'merkitty',
            'event_last_date'	=> 'otettu',
            'person_name'	=> 'etunimi',
            'person_lastname'	=> 'sukunimi'
        );
        
        //Korjaa ääkköset takaisin
        $filter = urldecode($filter);
        
        //Hae tiedot
        $results = $this->Territory_model->search($data['database_fields'], $sort_by, $sort_order, $chkbox_sel, $date_sel, $code_sel, $bt_switch, '0');
        
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
        
        //$this->load->view('territory_edit', $data);
        $this->load->view('territory_view', $data);
    }
    
    public function display_marklist() 
    {
        $data['database_fields'] = array(
            'alue_code'		=> 'alue_koodi',
            'alue_detail'	=> 'alue_nimi',
            'alue_location'	=> 'alue_tietoja',
            'lainassa'		=> 'alue_lainassa',
            'event_last_date'	=> 'alue_muutospvm',
            'mark_date'	=> 'event_lastdate',
            'person_name'	=> 'etunimi',
            'person_lastname'	=> 'sukunimi'
        );
        
        //Hae tiedot
        $results = $this->Territory_model->search_mark_exhort($data['database_fields']);
        
        $data['terr_mark_list'] = $this->create_terr_mark_rows($results);
        $data['num_results'] = $results['num_rows'];
        
        $this->load->view('territory_mark_view', $data);
    }
    
    public function display_co_report()
    {
        //Setting parameters to view page
        $data['report_date'] = $this->session->userdata('circuit_week_start');
        
        $data['circuit_week_start'] = $this->session->userdata('circuit_week_start');
        $data['circuit_week_end'] = $this->session->userdata('circuit_week_end');
        
        //Käytä raportin vertailupäivänä kierrosviikon alkupäivää
        $date_sw = '1';
        
        $isCwComing = $this->vertaaPvm();
        $data['is_cw_coming'] = $isCwComing;
        
        if (!$isCwComing) {
            //Jos kierrosviikko on mennyt, raporttipäiväksi kuluva päivä.
            //Vierailun ajankohtaa ei mäytetä.
            $today = date("Y-m-d");
            $unixDate = strtotime($today);
            $data['report_date'] = date('j.n.Y', $unixDate);
            $date_sw = '0'; //Vertailupäiväksi kuluva päivä
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
        $cwStart = $this->session->userdata('circuit_week_start');
        
        $today_time = strtotime($today);
        $expire_time = strtotime($cwStart);
        
        if ($expire_time < $today_time) 
        { 
            $isFuture = false;
        }
        
        return $isFuture;
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
                        
                    case "mark_date":
                        $mark_date = new DateTime($value);
                        $resultrow->mark_date = $mark_date->format('j.n.Y');
                        break;
                        
                    case "person_name":
                        break;
                        
                    case "person_lastname":
                        if ($aluerivi->lainassa == "1") {
                            if ($this->session->userdata('name_presentation') == "0") {
                                //0 = firstname lsatname, 1 = lastmame, firstname; (default)
                                $name_delim = ' ';
                                $resultrow->name = $aluerivi->person_name . $name_delim . $value;
                            } else {
                                $name_delim = ', ';
                                $resultrow->name = $value . $name_delim . $aluerivi->person_name;
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
                        
                    case "mark_date":
                        $mark_date = new DateTime($value);
                        $territoty['mark_date'] = $mark_date->format('j.n.Y');
                        break;
                        
                    case "event_last_date":
                        $event_last_date = new DateTime($value);
                        $territoty['event_last_date'] = $event_last_date->format('j.n.Y');
                        break;
                        
                    case "person_name":
                        break;
                        
                    case "person_lastname":
                        if ($this->session->userdata('name_presentation') == "0") {
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
        
    public function update ($alue_numero, $filter = '') 
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
            'mark_date',
            'person_name',
            'person_lastname'
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
                    
                case "mark_date":
                    $mark_date = new DateTime($value);
                    $resultrow->mark_date = $mark_date->format('j.n.Y');
                    break;
                    
                case "person_name":
                    break;
                    
                case "person_lastname":
                    if ($alue_rivi['lainassa'] == "1") {
                        if ($this->session->userdata('name_presentation') == "0") {
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
        
        $this->load->view('terr_mark', $resultrow);
        
    }
    
    public function update_alue()
    {
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
            
            $this->Event_model->insert($event_data_new);
            //Merkitään alue käydyksi, palautuneeksi
            $alue_kayty = true;
        } else { //lainaus
            if ($this->input->post('lainassa_old') == "1") { //Alueen vaihto
                //Lisää alueen palautumistapahtuma
                $event_data_old = array(
                    'event_type' => "2",
                    'event_date' => $new_lastdate,
                    'event_user' => $person_id_old,
                    'event_alue' => $alue_id
                );
                
                $this->Event_model->insert($event_data_old);
                
                //Merkitään alue käydyksi ja palautuneeksi vain, jos lainaaja vaihtuu
                if ($person_id_old != $person_id_new) {
                    $alue_kayty = true;
                }
            }
            $event_data_new = array(
                'event_type' => "1",
                'event_date' => $new_lastdate,
                'event_user' => $person_id_new,
                'event_alue' => $alue_id
            );
            
            $this->Event_model->insert($event_data_new);
        }
               
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
    
    public function get_person_id($name) 
    {
        $person_id = 0;
        
        //Jos nimi ei ole tyhjä, haetaan id
        if (!empty($name)) {
            $nimet = preg_split("/[, ]+/", $name);

            if ($this->session->userdata('name_presentation') == "0") {
                //0 = firstname lsatname, 1 = lastmame, firstname; (default)
                $etunimi = $nimet[0];
                $sukunimi = $nimet[1];
            } else {
                $etunimi = $nimet[1];
                $sukunimi = $nimet[0];
            }
            
            $data = array(
                'person_name' => $etunimi,
                'person_lastname' => $sukunimi,
                'person_group' => '0'
            );
            
            //Onko nimi kannassa?
            $person_id = $this->Territory_model->get_name_id($etunimi, $sukunimi);
            if ($person_id < 0) {
                //Ei, lisätään
                $this->Territory_model->insert_person ($data);
                $person_id = $this->Territory_model->get_name_id($etunimi, $sukunimi);
            }
        }
        
        return $person_id;
    }
    
    public function check_territory() 
    {
        $action = $this->input->post('action');
        if ($action == 'Päivitä') {
            // set validation rules
            $rules = array(
                array('field' => 'djnimi',
                    'label' => 'Kenellä',
                    'rules' => 'callback_verify_alue')
            ) ;
            // check input data
            $this->form_validation->set_rules($rules);
            
            if ($this->form_validation->run() == false) {
                $this->update($this->input->post('alue_code'));
            } else {
                $this->update_alue();
            }
        }
        if ($action == 'Paluu') {
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
        else {
            return true;
        }
    }
    
    public function update_territories() 
    {
        $fieldA = $this->input->post('numero');
        $fieldB = $this->input->post('alue_nimi');
        $fieldC = $this->input->post('lisätieto');
        $fieldD = $this->input->post('lainassa');
        $fieldE = $this->input->post('käyty');
        $fieldF = $this->input->post('otettu');
        $fieldG = $this->input->post('kenellä');
        $r = array();
        
        for ($i = 0; $i < sizeof($fieldA); $i++) {
            $array = array('numero' => $fieldA[$i],
                'alue_nimi' => $fieldB[$i],
                'lisätieto' => $fieldC[$i],
                'lainassa' => $fieldD[$i],
                'käyty' => $fieldE[$i],
                'otettu' => $fieldF[$i],
                'kenellä' => $fieldG[$i]
            );
            $r[] = $array;
        }
        
        print_r($r);
   
        
        
//         $count = count($this->input->post('numero'));
//         for ($i = 0; $i < $count; $i++) {
//             $data[] = array(
//                 'numero' => $this->input->post('numero'),
//                 'alue_nimi'	=>  $this->input->post('alue_nimi'),
//                 'lisätieto'	=> $this->input->post('lisätieto'),
//                 'lainassa'	=> $this->input->post('lainassa'),
//                 'käyty'	    => $this->input->post('käyty'),
//                 'otettu'	=> $this->input->post('otettu'),
//                 'kenellä'	=> $this->input->post('kenellä')
//             );
//         }
        
//         print_r($data);
      return 0;
    }
    
    public function index()
    {
        $this->display_frontpage();
    }
}