<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Event_model extends CI_Model 
{
    function __construct() {
        parent::__construct();
    }
    
    function search_headers($fields, $code = 'A', $limit, $offset) 
	{
        $fetch_columns = array();
        foreach ($fields as $field_name => $field_display) {
            $fetch_columns[] = $field_name;
        }
        
        // Results query
        $query = $this->db->select($fetch_columns)
            ->from('alue')
            ->like('alue_code', $code)
            ->limit($limit, $offset);
        
		$query = $this->db->order_by("SUBSTR(alue_code FROM 1 FOR 1)", "ASC");
		$query = $this->db->order_by("CAST(SUBSTR(alue_code FROM 2) AS UNSIGNED)", "ASC");
		
        $ret['rows'] = $query->get()->result();
        
        //count query
        $ret['num_rows'] = count($ret['rows']);
        
        return $ret;
    }
    
    function get_alue_count($code) 
    {
        //count query
        $query = $this->db->select('COUNT(*) as count', FALSE)
        ->from('alue')
        ->like('alue_code', $code);
        
        $res = $query->get()->result();
        return ($res[0]->count);
    }
    
    function search_event_data($fields, $alue_id, $archive_time, $event_date_order) 
    {
        $limit = 25;
        
        $fetch_columns = array();
        foreach ($fields as $field_name => $field_display) {
            $fetch_columns[] = $field_name;
        }
        
        // Results query
        $query = $this->db->select($fetch_columns)
        ->from('alue_events')
        ->where('event_alue', $alue_id)
        ->limit($limit);
        
        // e.g. back_years = "-12 years"
        $back_years = -1 * $archive_time;
        $back_years .= " years";
        $date_back_years = strtotime($back_years);
        
        $limit_date = date ('Y-m-d' , $date_back_years);
        $this->db->where('event_date >=', $limit_date);
        
        $this->db->join('person', 'event_user = person.person_id');
        $this->db->join('alue', 'event_alue = alue.alue_id');
        
        $query = $this->db->order_by("event_id", $event_date_order);
        
        $ret['rows'] = $query->get()->result();
        
        return $ret;
    }
    
    function search_history($fields, $alue_id)
    {
        $fetch_columns = array();
        foreach ($fields as $field_name => $field_display) {
            $fetch_columns[] = $field_name;
        }
        
        // Results query
        $query = $this->db->select($fetch_columns)
        ->from('alue_events')
        ->where('event_date', $alue_id)
        ->limit($limit, $offset);
        
        $this->db->join('person', 'event_user = person.person_id');
        $this->db->join('alue', 'event_alue = alue.alue_id');
        
        $query = $this->db->order_by("event_alue", "ASC");
        $query = $this->db->order_by("event_date", "DESC");
        
        $ret['rows'] = $query->get()->result();
        
        return $ret;
    }
    
    public function insert ($data) {
        if ($this->db->insert("alue_events", $data)) {
            return true;
        }
    }
    
    public function delete($event_id) {
        if ($this->db->delete("alue_events", "event_id = ".$event_id)) {
            return true;
        }
    }
    
    public function get_terr_codes() 
    {
        $query = $this->db->select('distinct left(alue_code, 1) as letter')
        ->from('alue');
        
        $query = $this->db->order_by("letter", "ASC");
        
        $ret['rows'] = $query->get()->result();
        
        return $ret;
    }
    
    function tabulate_alue_events($event_results, $event_date_order)
    {
        $e = array();
        $event_result_row = new stdClass;
        
        $prev_event_type = "0";
        
        foreach ($event_results['rows'] as $event_row)
        {
            foreach ($event_row as $key=>$value)
            {
                switch ($key) {
                    case "alue_code":
                        if ($event_row->event_type == $prev_event_type) {
                            //Käsitellään tilanne, jossa tapahtuma puuttuu
                            if ($event_row->event_type == "1") {
                                if ($event_date_order == "ASC") {
                                    $e[] = $event_result_row;
                                    $event_result_row = new stdClass;
                                }
                            }
                            if ($event_row->event_type == "2") {
                                if ($event_date_order == "DESC") {
                                    $e[] = $event_result_row;
                                    $event_result_row = new stdClass;
                                }
                            }
                        }
                        $event_result_row->code = $value;
                        break;
                        
                    case "event_type":
                        break;
                        
                    case "event_date":
                        $event_date = new DateTime($value);
                        if ($event_row->event_type == "2") {
                            $event_result_row->returned = $event_date->format('j.n.Y');
                        } else if ($event_row->event_type == "1") {
                            $event_result_row->taken = $event_date->format('j.n.Y');
                        }
                        break;
                        
                    case "person_name":
                        break;
                        
                    case "person_lastname":
                        if ($this->session->userdata('name_presentation') == "0") {
                            //0 = firstname lsatname, 1 = lastmame, firstname; (default)
                            $name_delim = ' ';
                            $event_result_row->name = $event_row->person_name . $name_delim . $value;
                        } else {
                            $name_delim = ', ';
                            $event_result_row->name =  $value . $name_delim . $event_row->person_name;
                        }
                        
                        if ($event_date_order == "DESC") {
                            if ($event_row->event_type == "1") {
                                $e[] = $event_result_row;
                                $event_result_row = new stdClass;
                            }
                        }
                        if ($event_date_order == "ASC") {
                            if ($event_row->event_type == "2") {
                                $e[] = $event_result_row;
                                $event_result_row = new stdClass;
                            }
                        }
                        $prev_event_type = $event_row->event_type;
                        break;
                        
                    default:
                        break;
                } // switch
            } // foreach aluerivi
        }
        
        //Tarkistetaan viimeinen rivi. Jos se pn 'pariton', lisätään se mukaan
        if (count($event_results['rows']) > 0) {
            $last_row = end ($event_results['rows']);
            foreach ($last_row as $key=>$value) {
                switch ($key) {
                    case "alue_code":
                        $event_result_row->code = $value;
                        break;
                        
                    case "event_date":
                        $event_date = new DateTime($value);
                        if ($event_row->event_type == "2") {
                            $event_result_row->returned = $event_date->format('j.n.Y');
                        } else if ($event_row->event_type == "1") {
                            $event_result_row->taken = $event_date->format('j.n.Y');
                        }
                        break;
                        
                    case "person_name":
                        break;
                        
                    case "person_lastname":
                        if ($this->session->userdata('name_presentation') == "0") {
                            $name_delim = ' ';
                            $event_result_row->name = $event_row->person_name . $name_delim . $value;
                        } else {
                            $name_delim = ', ';
                            $event_result_row->name =  $value . $name_delim . $event_row->person_name;
                        }
                        
                        if ($event_date_order == "DESC") {
                            if ($event_row->event_type == "2") {
                                $e[] = $event_result_row;
                            }
                        }
                        if ($event_date_order == "ASC") {
                            if ($event_row->event_type == "1") {
                                $e[] = $event_result_row;
                            }
                        }
                        break;
                    default:
                        break;
                } // switch
            } // foreach last_row
        }
        
        return $e;
    }
    
    function tabulate($events_data)
    {
        $tab_result_rows = array();
        
        $terr_count=count($events_data);
        
        //Haetaan alue, jolla on eniten tapahtumia, ja poimitaan tämä määrä
        $maxEventCount = 0;
        
        foreach ($events_data as $alue) {
            $eventcount=count($alue);
            if ($eventcount > $maxEventCount) {
                $maxEventCount = $eventcount;
            }
        }
        
        //Käännetään haetut tiedot siten, että rivit muunnetaan sarakkeiksi
        for($idx=0;$idx<$maxEventCount;$idx++)
        {
            $tabrow_names = array();
            $tabrow_events = array();
            $tabrow = array();
            
            for($ai=0;$ai<$terr_count;$ai++) {
                //Asetetaan avain
                $names_key = 'name_' . $ai;
                $taken_key = 'taken_' . $ai;
                $returned_key = 'returned_' . $ai;
                
                //Jos aluetta vastaava tieto löytyy, asetetaan se arvoksi, muuten käytetään tyhjää
                if ($idx < count($events_data[$ai])) {
                    $names_value = $events_data[$ai][$idx]->name;
                    
                    if (property_exists($taken_value = $events_data[$ai][$idx], 'taken')) {
                        $taken_value = $events_data[$ai][$idx]->taken;
                    } else {
                        $taken_value = "";
                    }
                    
                    if (property_exists($events_data[$ai][$idx], 'returned')) {
                        $returned_value = $events_data[$ai][$idx]->returned;
                    } else {
                        $returned_value = "";
                    }
                } else {
                    $names_value = "";
                    $taken_value = "";
                    $returned_value = "";
                }
                
                //Lisätään avain ja arvo taulukkoon
                $tabrow_names[$names_key] = $names_value;
                $tabrow_events[$taken_key] = $taken_value;
                $tabrow_events[$returned_key] = $returned_value;
            }
            //Lisätään rivikohtaiset tiedot taulukoksi
            $tabrow_names_key = 'names';
            $tabrow[$tabrow_names_key] = $tabrow_names;
            
            $tabrow_events_key = 'dates';
            $tabrow[$tabrow_events_key] = $tabrow_events;
            
            //Lisätään rivin tiedot tulostaulukkoon
            $tab_result_rows[] = $tabrow;
        }
        
        return (object) $tab_result_rows;
    }
    
    public function get_latest_event_data($fetch_columns, $alue_id) 
    {
        // Results query
        $query = $this->db->select($fetch_columns)
        ->from('alue_events')
        ->where('event_alue', $alue_id);
        
        $this->db->join('(SELECT event_alue as max_event_alue, MAX(event_id) AS max_event_id FROM alue_events GROUP BY event_alue) ee', 'event_id = ee.max_event_id AND event_alue = ee.max_event_alue');
        
        $ret['rows'] = $query->get()->result();
        
        //count query
        $ret['num_rows'] = count($ret['rows']);
        
        return $ret;
    }
    
    public function get_latest_return_event_data($fetch_columns, $alue_id)
    {
        // Results query
        $query = $this->db->select($fetch_columns)
        ->from('alue_events')
        ->where('event_alue', $alue_id);
        
        $this->db->join('(SELECT event_alue as max_event_alue, MAX(event_id) AS max_event_id FROM alue_events WHERE event_type = "2" GROUP BY event_alue) ee', 'event_id = ee.max_event_id AND event_alue = ee.max_event_alue');
        
        $ret['rows'] = $query->get()->result();
        
        //count query
        $ret['num_rows'] = count($ret['rows']);
        
        return $ret;
    }
}