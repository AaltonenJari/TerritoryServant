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
    
    
    public function get_terr_codes() 
    {
        $query = $this->db->select('distinct left(alue_code, 1) as letter')
        ->from('alue');
        
        $query = $this->db->order_by("letter", "ASC");
        
        $ret['rows'] = $query->get()->result();
        
        return $ret;
    }
}