<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User_model extends CI_Model
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
        $sort_by = (in_array($sort_by, $sort_columns)) ? $sort_by : 'user_lastname';
        
        $fetch_columns = array();
        foreach ($fields as $field_name => $field_display) {
            $fetch_columns[] = $field_name;
        }
        
        // Results query
        $query = $this->db->select($fetch_columns)
        ->from('users');
        
        
        //Järjestys
        switch ($sort_by) {
            case "user_id":
                $query = $this->db->order_by($sort_by, $sort_order);
                break;
            
            case "user_firstname":
            case "user_lastname":
                $query = $this->db->order_by("user_lastname", $sort_order);
                $query = $this->db->order_by("user_firstname", $sort_order);
                break;
                
            case "user_username":
            case "user_password":
            case "user_email":
            case "user_admin":
                $query = $this->db->order_by($sort_by, $sort_order);
                $query = $this->db->order_by("user_lastname", "ASC");
                $query = $this->db->order_by("user_firstname", "ASC");
                break;
                
            default:
                $query = $this->db->order_by("user_lastname", $sort_order);
                $query = $this->db->order_by("user_firstname", $sort_order);
                break;
        } // switch
        
        
        $ret['rows'] = $query->get()->result();
        
        //count cuery
        $ret['num_rows'] = count($ret['rows']);
        
        return $ret;
    }
    
    function get_id_by_name($first_name, $last_name)
    {
        $name_id = -1;
        // Results query
        $query = $this->db->select('user_id')
        ->from('users');
        
        $this->db->where('user_firstname', $first_name);
        $this->db->where('user_lastname', $last_name);
        
        $result_array = $this->db->get()->result_array();
        if (!empty($result_array)) {
            $name_id = $result_array[0]['user_id'];
        } else {
            $name_id = -1;
        }
        return $name_id;
    }
    
    public function row_exists($key_id)
    {
        $query = $this->db->select('COUNT(*) as count', FALSE)
        ->from('users');
        $this->db->where('user_id', $key_id);
        
        $res2 = $query->get()->result();
        
        return ($res2[0]->count);
    }
    
    public function username_exists($username) 
    {
        $query = $this->db->select('COUNT(*) as count', FALSE)
        ->from('users');
        $this->db->where('user_username', $username);
        
        $res2 = $query->get()->result();
        
        return ($res2[0]->count);
    }
    
    function get_row_by_key($columns, $key_id)
    {
        // Results query
        $query = $this->db->select($columns)
        ->from('users');
        $this->db->where('user_id', $key_id);
        
        $reault_array = $this->db->get()->result_array();
        if (!empty($result_array)) {
            return $result_array[0];
        } else {
            return null;
        }
    }
    
    public function insert($data) {
        if ($this->db->insert("users", $data)) {
            return true;
        }
    }
    
    public function delete($key_id) 
    {
        $this->db->where('user_id',$key_id); 
        $result = $this->db->delete('users');
    }
    
    public function update($data, $key) 
    {
        $this->db->set($data);
        $this->db->where("user_id", $key);
        $this->db->update("users", $data);
    }
}