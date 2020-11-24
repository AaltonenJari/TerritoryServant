<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Territory_model extends CI_Model {
    function __construct() {
        parent::__construct();
    }
    
    function search($fields, $sort_by, $sort_order, $chkbox_sel, $date_sel, $code_sel = '0', $bt_switch = '0', $date_switch = '0') 
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
        $sort_columns[] = "terr_group"; //Lisätään viela lajittelu aluekoodin mukaan
        $sort_by = (in_array($sort_by, $sort_columns)) ? $sort_by : 'alue_code';
        
        $fetch_columns = array();
        foreach ($fields as $field_name => $field_display) {
            $fetch_columns[] = $field_name;
        }
        
        // Results query
        $query = $this->db->select($fetch_columns)
            ->from('alue');
        
        $this->db->join('(SELECT ee2.event_alue, event_user, ee2.event_date as mark_date FROM alue_events ee2 JOIN (SELECT event_alue, MAX(event_id) AS max_event_id FROM alue_events WHERE event_type = "2" GROUP BY event_alue) groupedee2 ON ee2.event_alue = groupedee2.event_alue AND ee2.event_id = groupedee2.max_event_id) e2', 'alue.alue_id = e2.event_alue','left'); 
        $this->db->join('(SELECT ee.event_alue, event_user, ee.event_date as event_last_date FROM alue_events ee JOIN (SELECT event_alue, MAX(event_id) AS max_event_id FROM alue_events WHERE event_type = "1" GROUP BY event_alue) groupedee ON ee.event_alue = groupedee.event_alue AND ee.event_id = groupedee.max_event_id) e', 'alue.alue_id = e.event_alue','left');
        $this->db->join('person', 'e.event_user = person.person_id','left');
        
        // lainassa = false
        if ($chkbox_sel == '1') {
            $this->db->where('lainassa', '0');
        }
        
        // lainassa = true
        if ($chkbox_sel == '2') {
            $this->db->where('lainassa', '1');
        }
        
        if ($date_switch == '0') {
            $srchDate = ""; // today
        } else {
            //Circuit week starting date
            $srchDate = date_format(date_create_from_format('j.n.Y', $this->session->userdata('circuitWeekStart')), 'Y-m-d');
        }
        
        switch ($date_sel) {
            case 0: //Ei rajausta
                $limit_date = "";
                break;
            
            case 1: // alue_lastdate < 12 monhts
            case 6: //Circuot overseer's report
                $date_12_months = strtotime($srchDate ." -12 months");
                $limit_date = date ('Y-m-d' , $date_12_months);
                $this->db->where('alue_lastdate <=', $limit_date);
                break;
                
            case 2: // alue_lastdate < 4 monhts
                $date_4_months = strtotime($srchDate ." -4 months");
                $limit_date = date ('Y-m-d' , $date_4_months);
                $this->db->where('alue_lastdate <=', $limit_date);
                break;
        
            case 3: //alue_lastdate < 6 monhts
                $date_6_months = strtotime($srchDate ." -6 months");
                $limit_date = date ('Y-m-d' , $date_6_months);
                $this->db->where('alue_lastdate <=', $limit_date);
                break;

            case 4: // event_last_date < 12 monhts
                $date_12_months = strtotime($srchDate ." -12 months");
                $limit_date = date ('Y-m-d' , $date_12_months);
                $this->db->where('event_last_date <=', $limit_date);
                break;
        
            case 5: // event_last_date < 4 monhts && alue_lastdate < 4 monhts
                $date_4_months = strtotime($srchDate ." -4 months");
                $limit_date = date ('Y-m-d' , $date_4_months);
                $this->db->where('event_last_date <=', $limit_date);
                $this->db->where('alue_lastdate <=', $limit_date);
                break;

            default: //Ei rajausta
                break;
        }
        
        // Onko rajattu alueryhmän mukaan?
        if ($code_sel != '0') {
            $this->db->like('alue_code', $code_sel);
        }
        
        //Otetaanko liikealueet mukaan?
        if ($bt_switch == '0') {
            $this->db->not_like('alue_code', 'L');
        }
        
        //Järjestys
        switch ($sort_by) {
            case "alue_code":
                $query = $this->db->order_by("SUBSTR(alue_code FROM 1 FOR 1)", $sort_order);
                $query = $this->db->order_by("CAST(SUBSTR(alue_code FROM 2) AS UNSIGNED)", $sort_order);
                break;
                
            case "alue_detail":
                $query = $this->db->order_by($sort_by, $sort_order);
                $query = $this->db->order_by("SUBSTR(alue_code FROM 1 FOR 1)", "ASC");
                $query = $this->db->order_by("CAST(SUBSTR(alue_code FROM 2) AS UNSIGNED)", "ASC");
                break;
                
            case "alue_location":
                $query = $this->db->order_by($sort_by, $sort_order);
                $query = $this->db->order_by("SUBSTR(alue_code FROM 1 FOR 1)", "ASC");
                $query = $this->db->order_by("CAST(SUBSTR(alue_code FROM 2) AS UNSIGNED)", "ASC");
                break;
                
            case "lainassa":
                $query = $this->db->order_by($sort_by, $sort_order);
                if ($chkbox_sel == '1') {
                    //Jos haetaan seurakuntaan olevia, järjestä ensin käyntipäivän mukaan
                    $query = $this->db->order_by("alue_lastdate", "ASC");
                    $query = $this->db->order_by("SUBSTR(alue_code FROM 1 FOR 1)", "ASC");
                    $query = $this->db->order_by("CAST(SUBSTR(alue_code FROM 2) AS UNSIGNED)", "ASC");
                } else {
                    //Jos haetaan lainassa olevia, järjestä ensin nimen mukaan
                    $query = $this->db->order_by("person_lastname", $sort_order);
                    $query = $this->db->order_by("person_name", $sort_order);
                    $query = $this->db->order_by("alue_lastdate", "ASC");
                    $query = $this->db->order_by("SUBSTR(alue_code FROM 1 FOR 1)", "ASC");
                    $query = $this->db->order_by("CAST(SUBSTR(alue_code FROM 2) AS UNSIGNED)", "ASC");
                }
                break;
                
            case "alue_lastdate":
                $query = $this->db->order_by($sort_by, $sort_order);
                $query = $this->db->order_by("SUBSTR(alue_code FROM 1 FOR 1)", "ASC");
                $query = $this->db->order_by("CAST(SUBSTR(alue_code FROM 2) AS UNSIGNED)", "ASC");
                break;
                
            case "event_last_date":
                $query = $this->db->order_by("lainassa", "DESC");
                $query = $this->db->order_by($sort_by, $sort_order);
                $query = $this->db->order_by("SUBSTR(alue_code FROM 1 FOR 1)", "ASC");
                $query = $this->db->order_by("CAST(SUBSTR(alue_code FROM 2) AS UNSIGNED)", "ASC");
                break;
                
            case "name":
                $query = $this->db->order_by("lainassa", "DESC");
                $query = $this->db->order_by("person_lastname", $sort_order);
                $query = $this->db->order_by("person_name", $sort_order);
                $query = $this->db->order_by("SUBSTR(alue_code FROM 1 FOR 1)", "ASC");
                $query = $this->db->order_by("CAST(SUBSTR(alue_code FROM 2) AS UNSIGNED)", "ASC");
                break;
                
            case "terr_group":
                $query = $this->db->order_by("SUBSTR(alue_code FROM 1 FOR 1)", "ASC");
                $query = $this->db->order_by("alue_lastdate", "ASC");
                $query = $this->db->order_by("CAST(SUBSTR(alue_code FROM 2) AS UNSIGNED)", "ASC");
                break;
            
            default:
                $query = $this->db->order_by("SUBSTR(alue_code FROM 1 FOR 1)", $sort_order);
                $query = $this->db->order_by("CAST(SUBSTR(alue_code FROM 2) AS UNSIGNED)", $sort_order);
                break;
        } // switch
        
        $ret['rows'] = $query->get()->result();
        
        //count query
        $ret['num_rows'] = count($ret['rows']);
        
        return $ret;
    }
    
    function getTerritoryCount($chkbox_sel, $date_sel, $code_sel = '0', $bt_switch = '0', $date_switch = '0') 
	{
		$query = $this->db->select('COUNT(*) as count', FALSE)
            ->from('alue');

        $this->db->join('(SELECT ee2.event_alue, event_user, ee2.event_date as mark_date FROM alue_events ee2 JOIN (SELECT event_alue, MAX(event_id) AS max_event_id FROM alue_events WHERE event_type = "2" GROUP BY event_alue) groupedee2 ON ee2.event_alue = groupedee2.event_alue AND ee2.event_id = groupedee2.max_event_id) e2', 'alue.alue_id = e2.event_alue','left');
        $this->db->join('(SELECT ee.event_alue, event_user, ee.event_date as event_last_date FROM alue_events ee JOIN (SELECT event_alue, MAX(event_id) AS max_event_id FROM alue_events GROUP BY event_alue) groupedee ON ee.event_alue = groupedee.event_alue AND ee.event_id = groupedee.max_event_id) e', 'alue.alue_id = e.event_alue','left');
        $this->db->join('person', 'e.event_user = person.person_id','left');
            
        // lainassa = false
		if ($chkbox_sel == '1') {
            $this->db->where('lainassa', '0');
        }
        
        // lainassa = true
        if ($chkbox_sel == '2') {
            $this->db->where('lainassa', '1');
        }
        
        if ($date_switch == '0') {
            $srchDate = ""; // today
        } else {
            //Circuit week starting date
            $srchDate = date_format(date_create_from_format('j.n.Y', $this->session->userdata('circuitWeekStart')), 'Y-m-d');
        }
        
        switch ($date_sel) {
            case 0: //Ei rajausta
                $limit_date = "";
                break;
                
            case 1: // alue_lastdate < 12 monhts
            case 6: //Circuot overseer's report    
                $date_12_months = strtotime($srchDate ." -12 months");
                $limit_date = date ('Y-m-d' , $date_12_months);
                $this->db->where('alue_lastdate <=', $limit_date);
                break;
                
            case 2: // alue_lastdate < 4 monhts
                $date_4_months = strtotime($srchDate ." -4 months");
                $limit_date = date ('Y-m-d' , $date_4_months);
                $this->db->where('alue_lastdate <=', $limit_date);
                break;
                
            case 3: //alue_lastdate < 6 monhts
                $date_6_months = strtotime($srchDate ." -6 months");
                $limit_date = date ('Y-m-d' , $date_6_months);
                $this->db->where('alue_lastdate <=', $limit_date);
                break;
                
            case 4: // event_last_date < 12 monhts
                $date_12_months = strtotime($srchDate ." -12 months");
                $limit_date = date ('Y-m-d' , $date_12_months);
                $this->db->where('event_last_date <=', $limit_date);
                break;
                
            case 5: // event_last_date < 4 monhts && alue_lastdate < 4 monhts
                $date_4_months = strtotime($srchDate ." -4 months");
                $limit_date = date ('Y-m-d' , $date_4_months);
                $this->db->where('event_last_date <=', $limit_date);
                 $this->db->where('alue_lastdate <=', $limit_date);
                break;
 
            default: //Ei rajausta
                break;
        }

        // Onko rajattu alueryhmän mukaan?
        if ($code_sel != '0') {
            $this->db->like('alue_code', $code_sel);
        }
        
        //Otetaanko liikealueet mukaan?
        if ($bt_switch == '0') {
            $this->db->not_like('alue_code', 'L');
        }
        
        $res2 = $query->get()->result();
        return ($res2[0]->count);
	}
	
	function get_borrowing_congs() 
	{
	    $query = $this->db->select('person_lastname, count(person_lastname) AS territory_count', FALSE)
	    ->from('alue');
	    $this->db->join('(SELECT ee.event_alue, event_user, ee.event_date FROM alue_events ee JOIN (SELECT event_alue, MAX(event_id) AS max_event_id FROM alue_events GROUP BY event_alue) groupedee ON ee.event_alue = groupedee.event_alue AND ee.event_id = groupedee.max_event_id) e', 'alue.alue_id = e.event_alue');
	    $this->db->join('person', 'e.event_user = person.person_id');

	    // lainassa = true
	    $this->db->where('lainassa', '1');

	    $this->db->where('person_name', 'srk');
	    
	    $this->db->group_by('person_lastname'); 
	    $this->db->order_by('person_lastname', 'ASC');
	
	    $ret['rows'] = $query->get()->result();
	    
	    return $ret;
	}
	
	function get_terr_group_count($code)
	{
	    //count query
	    $query = $this->db->select('COUNT(*) as count', FALSE)
	    ->from('alue')
	    ->like('alue_code', $code);
	    
	    $res = $query->get()->result();
	    return ($res[0]->count);
	}
	
	
	function get_alue_row($columns, $alue_numero, $event_save_switch) 
	{
	    // Results query
	    $query = $this->db->select($columns)
	    ->from('alue');
	    
	    if ($event_save_switch > 0) {
	        //Hae myös merkkaustapahtumat
	        $this->db->join('(SELECT ee2.event_alue, event_user, ee2.event_date as mark_date, ee2.event_type as return_type FROM alue_events ee2 JOIN (SELECT event_alue, event_type, MAX(event_id) AS max_event_id FROM alue_events WHERE event_type IN ("2","4") GROUP BY event_alue) groupedee2 ON ee2.event_alue = groupedee2.event_alue AND ee2.event_id = groupedee2.max_event_id) e2', 'alue.alue_id = e2.event_alue','left');
	        $this->db->join('(SELECT ee.event_alue, event_user, ee.event_date as event_last_date, ee.event_type as mark_type FROM alue_events ee JOIN (SELECT event_alue, event_type, MAX(event_id) AS max_event_id FROM alue_events WHERE event_type IN ("1","3") GROUP BY event_alue) groupedee ON ee.event_alue = groupedee.event_alue AND ee.event_id = groupedee.max_event_id) e', 'alue.alue_id = e.event_alue','left');
	    } else {
	        //Hae vain lainaukset ja palautukset
	        $this->db->join('(SELECT ee2.event_alue, event_user, ee2.event_date as mark_date, ee2.event_type as return_type FROM alue_events ee2 JOIN (SELECT event_alue, event_type, MAX(event_id) AS max_event_id FROM alue_events WHERE event_type = "2" GROUP BY event_alue) groupedee2 ON ee2.event_alue = groupedee2.event_alue AND ee2.event_id = groupedee2.max_event_id) e2', 'alue.alue_id = e2.event_alue','left');
	        $this->db->join('(SELECT ee.event_alue, event_user, ee.event_date as event_last_date, ee.event_type as mark_type FROM alue_events ee JOIN (SELECT event_alue, event_type, MAX(event_id) AS max_event_id FROM alue_events WHERE event_type = "1" GROUP BY event_alue) groupedee ON ee.event_alue = groupedee.event_alue AND ee.event_id = groupedee.max_event_id) e', 'alue.alue_id = e.event_alue','left');
	    }
	    $this->db->join('person', 'e.event_user = person.person_id','left');
	    
	    $this->db->where('alue_code', $alue_numero);
	        
	    $result_array = $this->db->get()->result_array();
	    
	    return $result_array[0];
	}
	
	function get_terr_id($terr_code)
	{
	    $terr_id = -1;
	    // Results query
	    $query = $this->db->select('alue_id')
	    ->from('alue');
	    
	    $this->db->where('alue_code', $terr_code);
	    
	    $result_array = $this->db->get()->result_array();
	    if (!empty($result_array)) {
	        $terr_id = $result_array[0]['alue_id'];
	    } else {
	        $terr_id = -1;
	    }
	    return $terr_id;
	}
	
	public function update($data, $old_terr_nbr) 
	{
	    $this->db->set($data);
	    $this->db->where("alue_code", $old_terr_nbr);
	    $this->db->update("alue", $data);
	}
}