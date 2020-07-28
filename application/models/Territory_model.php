<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Territory_model extends CI_Model {
    function __construct() {
        parent::__construct();
    }
    
    function search($fields, $sort_by, $sort_order, $chkbox_sel, $date_sel, $code_sel = '0', $bt_switch = '0', $date_switch = '0') 
    {
        $sort_order = ($sort_order == 'desc') ? 'desc' : 'asc';
        
        $sort_columns = array();
        foreach ($fields as $field_name => $field_display) {
            if ($field_display == "sukunimi") {
                $sort_columns[] = "name";
            } else {
                $sort_columns[] = $field_name;
            }
        }
        $sort_by = (in_array($sort_by, $sort_columns)) ? $sort_by : 'alue_code';
        
        $fetch_columns = array();
        foreach ($fields as $field_name => $field_display) {
            $fetch_columns[] = $field_name;
        }
        
        // Results query
        $query = $this->db->select($fetch_columns)
            ->from('alue');
        
        $this->db->join('(SELECT ee2.event_alue, event_user, ee2.event_date as mark_date FROM alue_events ee2 JOIN (SELECT event_alue, MAX(event_id) AS max_event_id FROM alue_events WHERE event_type = "2" GROUP BY event_alue) groupedee2 ON ee2.event_alue = groupedee2.event_alue AND ee2.event_id = groupedee2.max_event_id) e2', 'alue.alue_id = e2.event_alue','left'); 
        $this->db->join('(SELECT ee.event_alue, event_user, ee.event_date as event_last_date FROM alue_events ee JOIN (SELECT event_alue, MAX(event_id) AS max_event_id FROM alue_events GROUP BY event_alue) groupedee ON ee.event_alue = groupedee.event_alue AND ee.event_id = groupedee.max_event_id) e', 'alue.alue_id = e.event_alue','left');
        $this->db->join('person', 'e.event_user = person.person_id','left');
        
        // lainassa = false
        if ($chkbox_sel == '1') {
            $this->db->where('lainassa', '0');
        }
        
        // lainassa = true
        if ($chkbox_sel == '2') {
            $this->db->where('lainassa', '1');
        }
        
        if ($date_switch == '0') {
            $srchDate = ""; // today
        } else {
            //Circuit week starting date
            $srchDate = date_format(date_create_from_format('j.n.Y', $this->session->userdata('circuit_week_start')), 'Y-m-d');
        }
        
        // alue_lastdate < 12 monhts
        if ($date_sel == '1') {
            $date_12_months = strtotime($srchDate ." -12 months");
            $limit_date = date ('Y-m-d' , $date_12_months);
            $this->db->where('alue_lastdate <=', $limit_date);
        }
        // alue_lastdate < 4 monhts
        if ($date_sel == '2') {
            $date_4_months = strtotime($srchDate ." -4 months");
            $limit_date = date ('Y-m-d' , $date_4_months);
            $this->db->where('alue_lastdate <=', $limit_date);
        }
        
        // alue_lastdate < 6 monhts
        if ($date_sel == '3') {
            $date_6_months = strtotime($srchDate ." -6 months");
            $limit_date = date ('Y-m-d' , $date_6_months);
            $this->db->where('alue_lastdate <=', $limit_date);
        }
        
        // event_last_date < 12 monhts
        if ($date_sel == '4') {
            $date_12_months = strtotime($srchDate ." -12 months");
            $limit_date = date ('Y-m-d' , $date_12_months);
            $this->db->where('event_last_date <=', $limit_date);
        }
        
        // Onko rajattu alueryhmän mukaan?
        if ($code_sel != '0') {
            $this->db->like('alue_code', $code_sel);
        }
        
        //Otetaanko liikealueet mukaan?
        if ($bt_switch == '0') {
            $this->db->not_like('alue_code', 'L');
        }
        
        //Järjestys
        switch ($sort_by) {
            case "alue_code":
                $query = $this->db->order_by("SUBSTR(alue_code FROM 1 FOR 1)", $sort_order);
                $query = $this->db->order_by("CAST(SUBSTR(alue_code FROM 2) AS UNSIGNED)", $sort_order);
                break;
                
            case "alue_detail":
                $query = $this->db->order_by($sort_by, $sort_order);
                $query = $this->db->order_by("SUBSTR(alue_code FROM 1 FOR 1)", "ASC");
                $query = $this->db->order_by("CAST(SUBSTR(alue_code FROM 2) AS UNSIGNED)", "ASC");
                break;
                
            case "alue_location":
                $query = $this->db->order_by($sort_by, $sort_order);
                $query = $this->db->order_by("SUBSTR(alue_code FROM 1 FOR 1)", "ASC");
                $query = $this->db->order_by("CAST(SUBSTR(alue_code FROM 2) AS UNSIGNED)", "ASC");
                break;
                
            case "lainassa":
                $query = $this->db->order_by($sort_by, $sort_order);
                $query = $this->db->order_by("alue_lastdate", "ASC");
                $query = $this->db->order_by("SUBSTR(alue_code FROM 1 FOR 1)", "ASC");
                $query = $this->db->order_by("CAST(SUBSTR(alue_code FROM 2) AS UNSIGNED)", "ASC");
                break;
                
            case "alue_lastdate":
                $query = $this->db->order_by($sort_by, $sort_order);
                $query = $this->db->order_by("SUBSTR(alue_code FROM 1 FOR 1)", "ASC");
                $query = $this->db->order_by("CAST(SUBSTR(alue_code FROM 2) AS UNSIGNED)", "ASC");
                break;
                
            case "event_last_date":
                $query = $this->db->order_by("lainassa", "DESC");
                $query = $this->db->order_by($sort_by, $sort_order);
                $query = $this->db->order_by("SUBSTR(alue_code FROM 1 FOR 1)", "ASC");
                $query = $this->db->order_by("CAST(SUBSTR(alue_code FROM 2) AS UNSIGNED)", "ASC");
                break;
                
            case "name":
                $this->db->order_by("lainassa", "DESC");
                $this->db->order_by("person_lastname", $sort_order);
                $this->db->order_by("person_name", $sort_order);
                $query = $this->db->order_by("SUBSTR(alue_code FROM 1 FOR 1)", "ASC");
                $query = $this->db->order_by("CAST(SUBSTR(alue_code FROM 2) AS UNSIGNED)", "ASC");
                break;
                
            default:
                $query = $this->db->order_by("SUBSTR(alue_code FROM 1 FOR 1)", $sort_order);
                $query = $this->db->order_by("CAST(SUBSTR(alue_code FROM 2) AS UNSIGNED)", $sort_order);
                break;
        } // switch
        
        
        $ret['rows'] = $query->get()->result();
        
        //count query
        $ret['num_rows'] = count($ret['rows']);
        
        return $ret;
    }
    
    function getTerritoryCount($chkbox_sel, $date_sel, $code_sel = '0', $bt_switch = '0', $date_switch = '0') 
	{
		$query = $this->db->select('COUNT(*) as count', FALSE)
            ->from('alue');

        $this->db->join('(SELECT ee2.event_alue, event_user, ee2.event_date as mark_date FROM alue_events ee2 JOIN (SELECT event_alue, MAX(event_id) AS max_event_id FROM alue_events WHERE event_type = "2" GROUP BY event_alue) groupedee2 ON ee2.event_alue = groupedee2.event_alue AND ee2.event_id = groupedee2.max_event_id) e2', 'alue.alue_id = e2.event_alue','left');
        $this->db->join('(SELECT ee.event_alue, event_user, ee.event_date as event_last_date FROM alue_events ee JOIN (SELECT event_alue, MAX(event_id) AS max_event_id FROM alue_events GROUP BY event_alue) groupedee ON ee.event_alue = groupedee.event_alue AND ee.event_id = groupedee.max_event_id) e', 'alue.alue_id = e.event_alue','left');
        $this->db->join('person', 'e.event_user = person.person_id','left');
            
        // lainassa = false
		if ($chkbox_sel == '1') {
            $this->db->where('lainassa', '0');
        }
        
        // lainassa = true
        if ($chkbox_sel == '2') {
            $this->db->where('lainassa', '1');
        }
        
        if ($date_switch == '0') {
            $srchDate = ""; // today
        } else {
            //Circuit week starting date
            $srchDate = date_format(date_create_from_format('j.n.Y', $this->session->userdata('circuit_week_start')), 'Y-m-d');
        }
        
        // alue_lastdate < 12 monhts
        if ($date_sel == '1') {
            $date_12_months = strtotime($srchDate ." -12 months");
            $limit_date = date ('Y-m-d' , $date_12_months);
            $this->db->where('alue_lastdate <=', $limit_date);
        }
        // alue_lastdate < 4 monhts
        if ($date_sel == '2') {
            $date_4_months = strtotime($srchDate ." -4 months");
            $limit_date = date ('Y-m-d' , $date_4_months);
            $this->db->where('alue_lastdate <=', $limit_date);
        }
        
        // alue_lastdate < 6 monhts
        if ($date_sel == '3') {
            $date_6_months = strtotime($srchDate ." -6 months");
            $limit_date = date ('Y-m-d' , $date_6_months);
            $this->db->where('alue_lastdate <=', $limit_date);
        }

        // Onko rajattu alueryhmän mukaan?
        if ($code_sel != '0') {
            $this->db->like('alue_code', $code_sel);
        }
        
        //Otetaanko liikealueet mukaan?
        if ($bt_switch == '0') {
            $this->db->not_like('alue_code', 'L');
        }
        
        $res2 = $query->get()->result();
        return ($res2[0]->count);
	}
	
	function get_borrowing_congs() 
	{
	    $query = $this->db->select('person_lastname, count(person_lastname) AS territory_count', FALSE)
	    ->from('alue');
	    $this->db->join('(SELECT ee.event_alue, event_user, ee.event_date FROM alue_events ee JOIN (SELECT event_alue, MAX(event_id) AS max_event_id FROM alue_events GROUP BY event_alue) groupedee ON ee.event_alue = groupedee.event_alue AND ee.event_id = groupedee.max_event_id) e', 'alue.alue_id = e.event_alue');
	    $this->db->join('person', 'e.event_user = person.person_id');

	    // lainassa = true
	    $this->db->where('lainassa', '1');

	    $this->db->where('person_name', 'srk');
	    
	    $this->db->group_by('person_lastname'); 
	    $this->db->order_by('person_lastname', 'ASC');
	
	    $ret['rows'] = $query->get()->result();
	    
	    return $ret;
	}
	
	function get_terr_group_count($code)
	{
	    //count query
	    $query = $this->db->select('COUNT(*) as count', FALSE)
	    ->from('alue')
	    ->like('alue_code', $code);
	    
	    $res = $query->get()->result();
	    return ($res[0]->count);
	}
	
	
	function get_alue_row($columns, $alue_numero) 
	{
	    // Results query
	    $query = $this->db->select($columns)
	    ->from('alue');
	    
	    $this->db->join('(SELECT ee.event_alue, event_id, event_user, ee.event_date as mark_date FROM alue_events ee JOIN (SELECT event_alue, MAX(event_id) AS max_event_id FROM alue_events GROUP BY event_alue) groupedee ON ee.event_alue = groupedee.event_alue AND ee.event_id = groupedee.max_event_id) e', 'alue.alue_id = e.event_alue','left');
	    $this->db->join('person', 'e.event_user = person.person_id','left');
	    
	    $this->db->where('alue_code', $alue_numero);
	        
	    $result_array = $this->db->get()->result_array();
	    
	    return $result_array[0];
	}
	
	function get_person($columns, $person_id)
	{
	    // Results query
	    $query = $this->db->select($columns)
	    ->from('person');
	    $this->db->where('person_id', $person_id);
	    
	    $result_array = $this->db->get()->result_array();
	    return $result_array[0];
	}
	
	function get_lending_start_date($terr_nbr) 
	{
	    $lending_date = null;
	    $max_event_id = 0;
	    
	    // 1. haetaan viimeisen lainaajan käyttäjätunnus alueen tapahtumista
	    $columns = array(
	        'event_id',
	        'event_user',
	        'event_date',
	        'event_type'
	    );
	    
	    $query = $this->db->select($columns)
	    ->from('alue_events')
	    ->where('event_alue', $terr_nbr);
	    $this->db->join('(SELECT event_alue as max_event_alue, MAX(event_id) AS max_event_id FROM alue_events GROUP BY event_alue) ee', 'event_id = ee.max_event_id AND event_alue = ee.max_event_alue');

	    $result_array = $this->db->get()->result_array();
	    if (count($result_array) == 0) {
	        //Jos ei löydy, palataan
	        return null;
	    }
	    
	    $lending_event_type = $result_array[0]['event_type'];
	    $lender_id = $result_array[0]['event_user'];
	    $max_event_id = $result_array[0]['event_id'];
	    $event_date = $result_array[0]['event_date'];
	    
	    //Jos kortti ei ole lainassa
	    if ($lending_event_type == '2') {
	        // asetetaan lainauspvm = null, palataan
	        $lending_date = null;
	    } else {
	        //2. haetaan tapahtumatunnus nykyistä lainaajaa edeltävän lainaajan viimeiseltä tapahtumariviltä
	        $query = $this->db->select('MAX(event_id) AS max_event_id')
	        ->from('alue_events');
	        $this->db->where('event_user !=', $lender_id);
	        $this->db->where('event_alue', $terr_nbr);
	        $this->db->group_by("event_user");
	        $this->db->order_by("max_event_id", "DESC");
	        $this->db->limit(1); 
	        
	        $result_array = $this->db->get()->result_array();
	        //Jos edellisiä lainaajia löytyi, käytetään löytynyttä tapahtumatunnusta
	        if (count($result_array) > 0) {
	            $max_event_id = $result_array[0]['max_event_id'];

	            //3. poimitaan päiväys nykyisen käyttäjän ensimmäiseltä tapahtumariviltä
	            $str_join_query = "(SELECT event_alue as min_event_alue, MIN(event_id) AS min_event_id FROM alue_events where event_id > " . $max_event_id . " GROUP BY event_alue) ee";
	            
	            $query = $this->db->select('event_date')
	            ->from('alue_events');
	            $this->db->join($str_join_query, 'event_id = ee.min_event_id AND event_alue = ee.min_event_alue');
	            $this->db->where('event_alue', $terr_nbr);
	            $this->db->where('event_id >', $max_event_id);
	            
	            $result_array = $this->db->get()->result_array();
	            $lending_date = $result_array[0]['event_date'];
	        } else {
	            //Käytetään nykyisen lainaajan lainauspäivää
	            $lending_date = $event_date;
	        }
	    }

	    return $lending_date;
	}
	
	function search_persons($fields, $sort_order) 
	{
	    $fetch_columns = array();
	    foreach ($fields as $field_name => $field_display) {
	        $fetch_columns[] = $field_name;
	    }
	    
	    // Results query
	    $query = $this->db->select($fetch_columns)
	    ->from('person');
	    $this->db->order_by("person_lastname", $sort_order);
	    $this->db->order_by("person_name", $sort_order);
	    
	    $ret['rows'] = $query->get()->result();
	    
	    //count query
	    $ret['num_rows'] = count($ret['rows']);
	    
	    return $ret;
	}
	
	function get_name_id($first_name, $last_name) 
	{
	    $name_id = -1;
	    // Results query
	    $query = $this->db->select('person_id')
	    ->from('person');
	    
	    $this->db->where('person_name', $first_name);
	    $this->db->where('person_lastname', $last_name);
	    
	    $result_array = $this->db->get()->result_array();
	    if (!empty($result_array)) {
	        $name_id = $result_array[0]['person_id'];
	    } else {
	        $name_id = -1;
	    }
	    return $name_id;
	}
	
	function get_terr_id($terr_code)
	{
	    $terr_id = -1;
	    // Results query
	    $query = $this->db->select('alue_id')
	    ->from('alue');
	    
	    $this->db->where('alue_code', $terr_code);
	    
	    $result_array = $this->db->get()->result_array();
	    if (!empty($result_array)) {
	        $terr_id = $result_array[0]['alue_id'];
	    } else {
	        $terr_id = -1;
	    }
	    return $terr_id;
	}
	
	public function insert_person ($data) 
	{
	    if ($this->db->insert("person", $data)) {
	        return true;
	    }
	}
	
	public function update_person($data, $person_id)
	{
	    $this->db->set($data);
	    $this->db->where("person_id", $person_id);
	    $this->db->update("person", $data);
	}
	
	public function update($data, $old_terr_nbr) 
	{
	    $this->db->set($data);
	    $this->db->where("alue_code", $old_terr_nbr);
	    $this->db->update("alue", $data);
	}
}