<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Maintenance_model extends CI_Model
{
    function __construct() {
        parent::__construct();
    }
    
    public function get_all_entries($fields, $sort_by, $sort_order) {
        $sort_order = ($sort_order == 'desc') ? 'desc' : 'asc';
        
        $sort_columns = array();
        foreach ($fields as $field_name => $field_display) {
            $sort_columns[] = $field_name;
        }
        $sort_by = (in_array($sort_by, $sort_columns)) ? $sort_by : 'alue_code';
        
        $fetch_columns = array();
        foreach ($fields as $field_name => $field_display) {
            $fetch_columns[] = $field_name;
        }
        
        // Results query
        $query = $this->db->select($fetch_columns)
        ->from('alue');
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
                
            default:
                $query = $this->db->order_by($sort_by, $sort_order);
                $query = $this->db->order_by("alue_id", $sort_order);
                break;
        } // switch
        
        
        $ret['rows'] = $query->get()->result();
        
        //count cuery
        $ret['num_rows'] = $this->getRowCount();
        
        return $ret;
    }
    
    public function getRowCount() {
        $query = $this->db->select('COUNT(*) as count', FALSE)
        ->from('alue');
        
        $res2 = $query->get()->result();
        return ($res2[0]->count);
    }
        
    public function insert($data) {
        if ($this->db->insert("alue", $data)) {
            return true;
        }
    }
    
    public function delete($alue_code) {
        if ($this->db->delete("alue", "alue_code = ".$alue_code)) {
            return true;
        }
    }
    
    public function update($data,$old_alue_code) {
        $this->db->set($data);
        $this->db->where("alue_code", $old_alue_code);
        $this->db->update("alue", $data);
    }
    
}