<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Maintenance_model extends CI_Model
{
    function __construct() {
        parent::__construct();
    }
    
    public function search($fields, $sort_by, $sort_order, $code_sel)
    {
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
        $this->db->join('(SELECT event_alue, count(*) AS event_count FROM alue_events GROUP BY event_alue) ev', 'ev.event_alue = alue.alue_id','left');
        
        // Onko rajattu alueryhmän mukaan?
        if ($code_sel != '0') {
            $this->db->like('alue_code', $code_sel);
        }
        
        //Järjestys
        switch ($sort_by) {
            case "alue_code":
                $query = $this->db->order_by("SUBSTR(alue_code FROM 1 FOR 1)", $sort_order);
                $query = $this->db->order_by("CAST(SUBSTR(alue_code FROM 2) AS UNSIGNED)", $sort_order);
                break;
                
            case "alue_detail":
            case "alue_location":
            case "alue_taloudet":
            case "event_count":
                $query = $this->db->order_by($sort_by, $sort_order);
                $query = $this->db->order_by("SUBSTR(alue_code FROM 1 FOR 1)", "ASC");
                $query = $this->db->order_by("CAST(SUBSTR(alue_code FROM 2) AS UNSIGNED)", "ASC");
                break;
                
            default:
                $query = $this->db->order_by("SUBSTR(alue_code FROM 1 FOR 1)", $sort_order);
                $query = $this->db->order_by("CAST(SUBSTR(alue_code FROM 2) AS UNSIGNED)", $sort_order);
                break;
        } // switch
        
        
        $ret['rows'] = $query->get()->result();
        
        //count cuery
        $ret['num_rows'] = count($ret['rows']);
        
        return $ret;
    }
    
    public function get_first_vacant_number($terr_group_code) 
    {
        //Oletus: ensimmäinen on vapaa
        $terr_nbr = $terr_group_code . "1";
        
        $found = $this->row_exists($terr_nbr);
        if ($found > 0) {
            //Jos löytyi, haetaan ensimmäinen vapaa kolo
            $terr_nbr = $this->get_first_hole($terr_group_code);
        }
        
        return $terr_nbr;
    }
    
    public function get_first_hole($terr_group_code)
    {
        $limit = 1;
        
        // Results query
        $query = $this->db->select("CONCAT(SUBSTR(p.alue_code FROM 1 FOR 1), CAST(SUBSTR(p.alue_code FROM 2) AS UNSIGNED) + 1) AS top")
        ->from('alue as p');
        $this->db->join('alue as alt', 'CAST(SUBSTR(alt.alue_code FROM 2) AS UNSIGNED) = CAST(SUBSTR(p.alue_code FROM 2) AS UNSIGNED) + 1 AND SUBSTR(alt.alue_code FROM 1 FOR 1) = SUBSTR(p.alue_code FROM 1 FOR 1)','left');
        $this->db->where('alt.alue_code',null);
        $this->db->where('SUBSTR(p.alue_code FROM 1 FOR 1) = ',$terr_group_code);
        $this->db->order_by("CAST(SUBSTR(p.alue_code FROM 2) AS UNSIGNED)", "ASC");
        $this->db->limit($limit);
        
        $reault_array = $this->db->get()->result_array();
        $terr_nbr = $reault_array[0]['top'];
        return $terr_nbr;
    }
    
    public function row_exists($terr_nbr) 
    {
        $query = $this->db->select('COUNT(*) as count', FALSE)
        ->from('alue');
        $this->db->where('alue_code', $terr_nbr);
        
        $res2 = $query->get()->result();
        
        return ($res2[0]->count);
    }
    
    function get_row_by_key($columns, $terr_nbr)
    {
        // Results query
        $query = $this->db->select($columns)
        ->from('alue');
        
        $this->db->where('alue_code', $terr_nbr);
        
        $reault_array = $this->db->get()->result_array();
        
        return $reault_array[0];
    }
    
    public function insert($data) {
        if ($this->db->insert("alue", $data)) {
            return true;
        }
    }
    
    public function delete($terr_nbr) 
    {
        $this->db->where('alue_code',$terr_nbr); 
        $result = $this->db->delete('alue');
    }
    
    public function update($data, $key) 
    {
        $this->db->set($data);
        $this->db->where("alue_code", $key);
        $this->db->update("alue", $data);
    }
    
}