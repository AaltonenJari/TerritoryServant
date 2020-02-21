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
                
            default:
                $query = $this->db->order_by($sort_by, $sort_order);
                $query = $this->db->order_by("alue_id", $sort_order);
                break;
        } // switch
        
        
        $ret['rows'] = $query->get()->result();
        
        //count cuery
        $ret['num_rows'] = count($ret['rows']);
        
        return $ret;
    }
    
    public function terrExists($terr_nbr) {
        $query = $this->db->select('COUNT(*) as count', FALSE)
        ->from('alue');
        $this->db->where('alue_code', $terr_nbr);
        
        $res2 = $query->get()->result();
        return ($res2[0]->count);
    }
    
    function get_alue_row($columns, $terr_nbr)
    {
        // Results query
        $query = $this->db->select($columns)
        ->from('alue');
        
        $this->db->where('alue_code', $terr_nbr);
        
        $reault_array = $this->db->get()->result_array();
        
        return $reault_array[0];
    }
    
    public function insert ($data) {
        if ($this->db->insert("alue", $data)) {
            return true;
        }
    }
    
    public function delete($terr_nbr) 
    {
        $this->db->where('alue_code',$terr_nbr); 
        $result = $this->db->delete('alue');
    }
    
    public function update($data, $old_terr_nbr) 
    {
        $this->db->set($data);
        $this->db->where("alue_code", $old_terr_nbr);
        $this->db->update("alue", $data);
    }
    
}