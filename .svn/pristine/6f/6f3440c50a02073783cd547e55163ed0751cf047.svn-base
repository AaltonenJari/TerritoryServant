<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Group_model extends CI_Model
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
        $sort_by = (in_array($sort_by, $sort_columns)) ? $sort_by : 'group_id';
        
        $fetch_columns = array();
        foreach ($fields as $field_name => $field_display) {
            $fetch_columns[] = $field_name;
        }
        
        // Results query
        $query = $this->db->select($fetch_columns)
        ->from('alue_group');
        $this->db->join('(SELECT person_group, count(*) AS person_count FROM person GROUP BY person_group) pp', 'pp.person_group = alue_group.group_id','left');
        
        //Järjestys
        switch ($sort_by) {
            case "group_id":
                $query = $this->db->order_by($sort_by, $sort_order);
                break;
            
            case "group_name":
            case "group_events":
            case "person_count":
                $query = $this->db->order_by($sort_by, $sort_order);
                $query = $this->db->order_by('group_id', $sort_order);
                break;
                
            default:
                $query = $this->db->order_by('group_id', $sort_order);
                break;
        } // switch
        
        
        $ret['rows'] = $query->get()->result();
        
        //count cuery
        $ret['num_rows'] = count($ret['rows']);
        
        return $ret;
    }
    
    function get_id_by_name($name)
    {
        $name_id = -1;
        // Results query
        $query = $this->db->select('group_id')
        ->from('alue_group');
        
        $this->db->where('group_name', $name);
        
        $result_array = $this->db->get()->result_array();
        if (!empty($result_array)) {
            $name_id = $result_array[0]['group_id'];
        } else {
            $name_id = -1;
        }
        return $name_id;
    }
    
    public function row_exists($key_id) 
    {
        $query = $this->db->select('COUNT(*) as count', FALSE)
        ->from('alue_group');
        $this->db->where('group_id', $key_id);
        
        $res2 = $query->get()->result();
        
        return ($res2[0]->count);
    }
    
    function get_row_by_key($columns, $key_id)
    {
        // Results query
        $query = $this->db->select($columns)
        ->from('alue_group');
        $this->db->where('group_id', $key_id);
        
        $reault_array = $this->db->get()->result_array();
        return $reault_array[0];
    }
    
    public function insert($data) {
        if ($this->db->insert("alue_group", $data)) {
            return true;
        }
    }
    
    public function delete($key_id) 
    {
        $this->db->where('group_id',$key_id); 
        $result = $this->db->delete('alue_group');
    }
    
    public function update($data, $key) 
    {
        $this->db->set($data);
        $this->db->where("group_id", $key);
        $this->db->update("alue_group", $data);
    }
 }