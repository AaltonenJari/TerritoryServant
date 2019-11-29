<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Territory_model extends CI_Model {
    function __construct() {
        parent::__construct();
    }
    
    function get_all_entries() {
        $query = $this->db->get('alue');
        $results = array();
        foreach ($query->result() as $result) {
            $results[] = $result;
        }
        return $results;
    }
    
    function search($fields, $sort_by, $sort_order, $chkbox_sel, $date_sel) 
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
        
        $this->db->join('(SELECT ee.event_alue, event_user, ee.event_date FROM alue_events ee JOIN (SELECT event_alue, MAX(event_id) AS max_event_id FROM alue_events GROUP BY event_alue) groupedee ON ee.event_alue = groupedee.event_alue AND ee.event_id = groupedee.max_event_id) e', 'alue.alue_id = e.event_alue');
        $this->db->join('person', 'e.event_user = person.person_id');
        
        // lainassa = false
        if ($chkbox_sel == '1') {
            $this->db->where('lainassa', '0');
        }
        
        // lainassa = true
        if ($chkbox_sel == '2') {
            $this->db->where('lainassa', '1');
        }
        
        $todayDate = time();
        
        // alue_lastdate < 12 monhts
        if ($date_sel == '1') {
            $date_12_months = strtotime("-12 months");
            $limit_date = date ('Y-m-d' , $date_12_months);
            $this->db->where('alue_lastdate <=', $limit_date);
        }
        // alue_lastdate < 4 monhts
        if ($date_sel == '2') {
            $date_4_months = strtotime("-4 months");
            $limit_date = date ('Y-m-d' , $date_4_months);
            $this->db->where('alue_lastdate <=', $limit_date);
        }
        
        // alue_lastdate < 6 monhts
        if ($date_sel == '3') {
            $date_6_months = strtotime("-6 months");
            $limit_date = date ('Y-m-d' , $date_6_months);
            $this->db->where('alue_lastdate <=', $limit_date);
        }
        
        if ($this->session->userdata('sivutunnus') == 1) {
            //Etusivu: Älä ota mukaan liikealueita
            $this->db->not_like('alue_code', 'L');
        }
        
        //Järjestys
        switch ($sort_by) {
            case "alue_code":
                $query = $this->db->order_by("alue_id", $sort_order);
                break;
                
            case "alue_detail":
                $query = $this->db->order_by($sort_by, $sort_order);
                $query = $this->db->order_by("alue_id", "ASC");
                break;
                
            case "alue_location":
                $query = $this->db->order_by($sort_by, $sort_order);
                $query = $this->db->order_by("alue_id", "ASC");
                break;
                
            case "lainassa":
                $query = $this->db->order_by($sort_by, $sort_order);
                $query = $this->db->order_by("alue_lastdate", "ASC");
                $query = $this->db->order_by("alue_id", "ASC");
                break;
                
            case "alue_lastdate":
                $query = $this->db->order_by($sort_by, $sort_order);
                $query = $this->db->order_by("alue_id", "ASC");
                break;
                
            case "event_date":
                $query = $this->db->order_by("lainassa", "DESC");
                $query = $this->db->order_by($sort_by, $sort_order);
                $query = $this->db->order_by("alue_id", "ASC");
                break;
                
            case "name":
                $query = $this->db->order_by("lainassa", "DESC");
                $query = $this->db->order_by("person_lastname", $sort_order);
                $query = $this->db->order_by("person_name", $sort_order);
                $query = $this->db->order_by("alue_id", "ASC");
                break;
                
            default:
                $query = $this->db->order_by("alue_id", $sort_order);
                break;
        } // switch
        
        
        $ret['rows'] = $query->get()->result();
        
        //count query
        $ret['num_rows'] = $this->getRowCount($chkbox_sel, $date_sel);
        
        return $ret;
    }
    
	function getRowCount($chkbox_sel, $date_sel) 
	{
		$query = $this->db->select('COUNT(*) as count', FALSE)
            ->from('alue');

        // lainassa = false
		if ($chkbox_sel == '1') {
            $this->db->where('lainassa', '0');
        }
        
        // lainassa = true
        if ($chkbox_sel == '2') {
            $this->db->where('lainassa', '1');
        }
        
        $todayDate = time();
        
        // alue_lastdate < 12 monhts
        if ($date_sel == '1') {
            $date_12_months = strtotime("-12 months");
            $limit_date = date ('Y-m-d' , $date_12_months);
            $this->db->where('alue_lastdate <=', $limit_date);
        }
        // alue_lastdate < 4 monhts
        if ($date_sel == '2') {
            $date_4_months = strtotime("-4 months");
            $limit_date = date ('Y-m-d' , $date_4_months);
            $this->db->where('alue_lastdate <=', $limit_date);
        }
        
        // alue_lastdate < 6 monhts
        if ($date_sel == '3') {
            $date_6_months = strtotime("-6 months");
            $limit_date = date ('Y-m-d' , $date_6_months);
            $this->db->where('alue_lastdate <=', $limit_date);
        }

        $res2 = $query->get()->result();
        return ($res2[0]->count);
	}
	
	function search_mark_exhort($fields)
	{
	    $fetch_columns = array();
	    foreach ($fields as $field_name => $field_display) {
	        $fetch_columns[] = $field_name;
	    }
	    
	    // Results query
	    $query = $this->db->select($fetch_columns)
	    ->from('alue');
	    
	    $this->db->join('(SELECT ee.event_alue, event_user, ee.event_date FROM alue_events ee JOIN (SELECT event_alue, MAX(event_id) AS max_event_id FROM alue_events GROUP BY event_alue) groupedee ON ee.event_alue = groupedee.event_alue AND ee.event_id = groupedee.max_event_id) e', 'alue.alue_id = e.event_alue');
	    $this->db->join('person', 'e.event_user = person.person_id');
	    
	    // lainassa = true
        $this->db->where('lainassa', '1');
	    
	    $todayDate = time();
	    
	    
	    // alue_lastdate < 4 monhts
	    $date_4_months = strtotime("-4 months");
	    $limit_date_4_months = date ('Y-m-d' , $date_4_months);
	    $this->db->where('event_date <=', $limit_date_4_months);
	    
	    // alue_lastdate < 12 monhts
	    $date_12_months = strtotime("-12 months");
	    $limit_date_12_months = date ('Y-m-d' , $date_12_months);
	    $this->db->or_where('alue_lastdate <=', $limit_date_12_months);
	    
	    //Järjestys
	    $query = $this->db->order_by("person_lastname", "ASC");
	    $query = $this->db->order_by("person_name", "ASC");
	    $query = $this->db->order_by("event_date", "DESC");
	    
	    $ret['rows'] = $query->get()->result();
	    
	    //count query
	    $ret['num_rows'] = $this->getExhortCount();
	    
	    return $ret;
	}
	
	function getExhortCount()
	{
	    $query = $this->db->select('COUNT(*) as count', FALSE)
	    ->from('alue');
	    
	    $this->db->join('(SELECT ee.event_alue, event_user, ee.event_date FROM alue_events ee JOIN (SELECT event_alue, MAX(event_id) AS max_event_id FROM alue_events GROUP BY event_alue) groupedee ON ee.event_alue = groupedee.event_alue AND ee.event_id = groupedee.max_event_id) e', 'alue.alue_id = e.event_alue');
	    $this->db->join('person', 'e.event_user = person.person_id');
	    
	    $todayDate = time();
	    
	    // alue_lastdate < 4 monhts
	    $date_4_months = strtotime("-4 months");
	    $limit_date_4_months = date ('Y-m-d' , $date_4_months);
	    $this->db->where('event_date <=', $limit_date_4_months);
	    
	    // alue_lastdate < 12 monhts
	    $date_12_months = strtotime("-12 months");
	    $limit_date_12_months = date ('Y-m-d' , $date_12_months);
	    $this->db->or_where('alue_lastdate <=', $limit_date_12_months);
	    
	    
	    $res2 = $query->get()->result();
	    return ($res2[0]->count);
	}
	
	
	
	function get_alue_row($columns, $alue_numero) 
	{
	    // Results query
	    $query = $this->db->select($columns)
	    ->from('alue');
	    
	    $this->db->join('(SELECT ee.event_alue, event_user, ee.event_date FROM alue_events ee JOIN (SELECT event_alue, MAX(event_id) AS max_event_id FROM alue_events GROUP BY event_alue) groupedee ON ee.event_alue = groupedee.event_alue AND ee.event_id = groupedee.max_event_id) e', 'alue.alue_id = e.event_alue');
	    $this->db->join('person', 'e.event_user = person.person_id');
	    
	    $this->db->where('alue_code', $alue_numero);
	        
	    $result_array = $this->db->get()->result_array();
	    
	    return $result_array[0];
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
	
	public function insert_person ($data) {
	    if ($this->db->insert("person", $data)) {
	        return true;
	    }
	}
	
	public function update($data, $old_terr_nbr) 
	{
	    $this->db->set($data);
	    $this->db->where("alue_code", $old_terr_nbr);
	    $this->db->update("alue", $data);
	}
}