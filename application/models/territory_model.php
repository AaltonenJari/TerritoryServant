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
            if ($field_display == "nimi") {
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
        
        
        $this->db->join('(SELECT ee.event_alue, event_user, ee.event_date FROM alue_events ee JOIN (SELECT event_alue, MAX(event_date) AS max_date FROM alue_events WHERE event_type = "2" GROUP BY event_alue) groupedee ON ee.event_alue = groupedee.event_alue AND ee.event_date = groupedee.max_date) e', 'alue.alue_id = e.event_alue');
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
        
        //count cuery
        $ret['num_rows'] = $this->getRowCount($chkbox_sel, $date_sel);
        
        return $ret;
    }

    function search_frontpage($fields, $sort_by, $sort_order, $chkbox_sel, $date_sel) 
    {
        $sort_order = ($sort_order == 'desc') ? 'desc' : 'asc';
        
        $sort_columns = array();
        foreach ($fields as $field_name => $field_display) {
            if ($field_display == "nimi") {
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
        
        
        
        $this->db->join('(SELECT ee.event_alue, event_user, ee.event_date FROM alue_events ee JOIN (SELECT event_alue, MAX(event_date) AS max_date FROM alue_events WHERE event_type = "2" GROUP BY event_alue) groupedee ON ee.event_alue = groupedee.event_alue AND ee.event_date = groupedee.max_date) e', 'alue.alue_id = e.event_alue');
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
        
        //Älä ota mukaan liikealueita
        $this->db->not_like('alue_code', 'L');
        
        //J�rjestys
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
	
	function get_alue_row($columns, $alue_numero) 
	{
	    // Results query
	    $query = $this->db->select($columns)
	    ->from('alue');
	    
	    $this->db->join('(SELECT ee.event_alue, event_user, ee.event_date FROM alue_events ee JOIN (SELECT event_alue, MAX(event_date) AS max_date FROM alue_events WHERE event_type = "2" GROUP BY event_alue) groupedee ON ee.event_alue = groupedee.event_alue AND ee.event_date = groupedee.max_date) e', 'alue.alue_id = e.event_alue');
	    $this->db->join('person', 'e.event_user = person.person_id');
	    
	    $this->db->where('alue_code', $alue_numero);
	        
	    $reault_array = $this->db->get()->result_array();
	    
	    return $reault_array[0];
	}
	
	public function update_alue($data) 
	{
	    print_r($data);
	    
	}
}