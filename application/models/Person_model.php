<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Person_model extends CI_Model
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
        $sort_by = (in_array($sort_by, $sort_columns)) ? $sort_by : 'person_lastname';
        
        $fetch_columns = array();
        foreach ($fields as $field_name => $field_display) {
            $fetch_columns[] = $field_name;
        }
        
        // Results query
        $query = $this->db->select($fetch_columns)
        ->from('person');
        $this->db->join('alue_group', 'person.person_group = alue_group.group_id','left');
        $this->db->join('(SELECT event_user, count(*) AS event_count FROM alue_events GROUP BY event_user) ev', 'ev.event_user = person.person_id','left');
        
        //Järjestys
        switch ($sort_by) {
            case "person_id":
                $query = $this->db->order_by($sort_by, $sort_order);
                break;
            
            case "person_name":
            case "person_lastname":
                $query = $this->db->order_by("person_lastname", $sort_order);
                $query = $this->db->order_by("person_name", $sort_order);
                break;
                
            case "group_name":
            case "person_leader":
            case "person_show":
                $query = $this->db->order_by($sort_by, $sort_order);
                $query = $this->db->order_by("person_lastname", $sort_order);
                $query = $this->db->order_by("person_name", $sort_order);
                break;
                
            case "event_count":
                $query = $this->db->order_by($sort_by, $sort_order);
                $query = $this->db->order_by("person_lastname", $sort_order);
                $query = $this->db->order_by("person_name", $sort_order);
                break;
            
            default:
                $query = $this->db->order_by("person_lastname", $sort_order);
                $query = $this->db->order_by("person_name", $sort_order);
                break;
        } // switch
        
        
        $ret['rows'] = $query->get()->result();
        
        //count cuery
        $ret['num_rows'] = count($ret['rows']);
        
        return $ret;
    }
    
    function get_person_id($first_name, $last_name)
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
    
    function get_person($columns, $person_id)
    {
        // Results query
        $query = $this->db->select($columns)
        ->from('person');
        $this->db->where('person_id', $person_id);
        
        $result_array = $this->db->get()->result_array();
        return $result_array[0];
    }
    
    public function row_exists($key_id) 
    {
        $query = $this->db->select('COUNT(*) as count', FALSE)
        ->from('person');
        $this->db->where('person_id', $key_id);
        
        $res2 = $query->get()->result();
        
        return ($res2[0]->count);
    }
    
    function get_row_by_key($columns, $key_id)
    {
        // Results query
        $query = $this->db->select($columns)
        ->from('person');
        
        $this->db->where('person_id', $key_id);
        
        $reault_array = $this->db->get()->result_array();
        
        return $reault_array[0];
    }
    
    public function insert($data) {
        if ($this->db->insert("person", $data)) {
            return true;
        }
    }
    
    public function delete($key_id) 
    {
        $this->db->where('person_id',$key_id); 
        $result = $this->db->delete('person');
    }
    
    public function update($data, $key) 
    {
        $this->db->set($data);
        $this->db->where("person_id", $key);
        $this->db->update("person", $data);
    }
    
    public function search_group($fields, $sort_by, $sort_order) 
    {
        $sort_order = ($sort_order == 'desc') ? 'desc' : 'asc';
        
        $sort_columns = array();
        foreach ($fields as $field_name => $field_display) {
            $sort_columns[] = $field_name;
        }
        $sort_by = (in_array($sort_by, $sort_columns)) ? $sort_by : 'group_id';
        
        $fetch_columns = array();
        foreach ($fields as $field_name => $field_display) {
            $fetch_columns[] = $field_name;
        }
        
        // Results query
        $query = $this->db->select($fetch_columns)
        ->from('alue_group');
        
        //Järjestys
        switch ($sort_by) {
            case "group_id":
                $query = $this->db->order_by($sort_by, $sort_order);
                break;
                
            case "group_name":
                $query = $this->db->order_by($sort_by, $sort_order);
                break;
                
            default:
                $query = $this->db->order_by("group_id", $sort_order);
                break;
        } // switch
        
        
        $ret['rows'] = $query->get()->result();
        
        //count cuery
        $ret['num_rows'] = count($ret['rows']);
        
        return $ret;
    }
}