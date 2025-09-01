<?php
class LoginModel extends CI_Model 
{
    public function login($name, $pass) 
    {
        $this->db->select('user_username','user_password');
        $this->db->from('users');
        $this->db->where('user_username', $name);
        $this->db->where('user_password', $pass);
        
        $query = $this->db->get();
        
        if ($query->num_rows() == 1) {
            return true;
        } else {
            return false;
        }
    }
    
    public function get_user_data($columns, $username)
    {
        // Results query
        $query = $this->db->select($columns)
        ->from('users');
        $this->db->where('user_username', $username);
        
        $reault_array = $this->db->get()->result_array();
        return $reault_array[0];
    }
    
}