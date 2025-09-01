<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Log_model extends CI_Model
{
    function __construct() {
        parent::__construct();
    }
    
    public function search($fields, $sort_by, $sort_order, $user_sel, $date_sel)
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
        $sort_by = (in_array($sort_by, $sort_columns)) ? $sort_by : 'user_username';
        
        $fetch_columns = array();
        foreach ($fields as $field_name => $field_display) {
            $fetch_columns[] = $field_name;
        }
        
        // Results query
        $query = $this->db->select($fetch_columns)
        ->from('event_log');
        $this->db->join('(SELECT user_id, user_username FROM users) us', 'us.user_id = event_log.log_user_id','left');
        $this->db->join('(SELECT alue_id, alue_code FROM alue) al', 'al.alue_id = event_log.log_event_terr','left');
        $this->db->join('(SELECT person_id, person_name, person_lastname FROM person) pp', 'pp.person_id = event_log.log_event_person','left');
        
        //rajaus käyttäjän mukaan
        if ($user_sel == '0') {
            $this->db->where('user_id is NULL');
        } else if ($user_sel != 'Kaikki') {
            $this->db->where('user_id', $user_sel);
        }

        //rajaus lokipäivän mukaan
        switch ($date_sel) {
            case "1": //alle 1/2 vuotta
                $date_searh = strtotime("" ." -6 months");
                $limit_date = date ('Y-m-d' , $date_searh);
                $this->db->where('log_timestamp >=', $limit_date);
                break;
                
            case "2": //alle 1 vuosi
                $date_searh = strtotime("" ." -12 months");
                $limit_date = date ('Y-m-d' , $date_searh);
                $this->db->where('log_timestamp >=', $limit_date);
                break;
            
            case "3": //alle 2 vuotta
                $date_searh = strtotime("" ." -24 months");
                $limit_date = date ('Y-m-d' , $date_searh);
                $this->db->where('log_timestamp >=', $limit_date);
                break;
                
            default:
                break;
        } // switch
        
        //Järjestys
        switch ($sort_by) {
            case "user_username":
                $query = $this->db->order_by($sort_by, $sort_order);
                $query = $this->db->order_by('log_id', 'asc');
                break;
            
            case "log_id":
            case "log_event_id":
            case "log_timestamp":
            case "log_event_type":
                $query = $this->db->order_by($sort_by, $sort_order);
                break;
                
            case "alue_code":
                $query = $this->db->order_by("SUBSTR(alue_code FROM 1 FOR 1)", $sort_order);
                $query = $this->db->order_by("CAST(SUBSTR(alue_code FROM 2) AS UNSIGNED)", $sort_order);
                $query = $this->db->order_by('log_id', $sort_order);
                break;
                
            case "name":
                $query = $this->db->order_by("person_lastname", $sort_order);
                $query = $this->db->order_by("person_name", $sort_order);
                $query = $this->db->order_by("SUBSTR(alue_code FROM 1 FOR 1)", "ASC");
                $query = $this->db->order_by("CAST(SUBSTR(alue_code FROM 2) AS UNSIGNED)", "ASC");
                $query = $this->db->order_by('log_id', $sort_order);
                break;
                
            case "event_type":
                $query = $this->db->order_by($sort_by, $sort_order);
                $query = $this->db->order_by('log_id', 'asc');
                break;
                
            case "event_date":
                $query = $this->db->order_by($sort_by, $sort_order);
                $query = $this->db->order_by("SUBSTR(alue_code FROM 1 FOR 1)", "ASC");
                $query = $this->db->order_by("CAST(SUBSTR(alue_code FROM 2) AS UNSIGNED)", "ASC");
                $query = $this->db->order_by('event_type', 'asc');
                break;
                
            case "log_operation_code":
                $query = $this->db->order_by($sort_by, $sort_order);
                $query = $this->db->order_by("SUBSTR(alue_code FROM 1 FOR 1)", "ASC");
                $query = $this->db->order_by("CAST(SUBSTR(alue_code FROM 2) AS UNSIGNED)", "ASC");
                $query = $this->db->order_by('log_id', 'asc');
                break;
                    
            default:
                $query = $this->db->order_by('log_id', $sort_order);
                break;
        } // switch
        
        
        $ret['rows'] = $query->get()->result();
        
        //count cuery
        $ret['num_rows'] = count($ret['rows']);
        
        return $ret;
    }
        
    public function row_exists($key_id) 
    {
        $query = $this->db->select('COUNT(*) as count', FALSE)
        ->from('event_log');
        $this->db->where('log_id', $key_id);
        
        $res2 = $query->get()->result();
        
        return ($res2[0]->count);
    }
    
    function get_row_by_key($columns, $key_id)
    {
        // Results query
        $query = $this->db->select($columns)
        ->from('event_log');
        $this->db->where('log_id', $key_id);
        
        $reault_array = $this->db->get()->result_array();
        return $reault_array[0];
    }
    
    public function insert($data) {
        if ($this->db->insert("event_log", $data)) {
            return true;
        }
    }
    
 }