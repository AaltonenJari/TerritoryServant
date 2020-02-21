<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Settings_model extends CI_Model 
{
    function __construct() {
        parent::__construct();
    }
	
	function get_settings()	
	{
        $ret['name_presentation'] = $this->session->userdata('name_presentation');
        
        $ret['event_date_order'] = $this->session->userdata('event_date_order');
        $ret['archive_time'] = $this->session->userdata('archive_time');
        $ret['bt_switch'] = $this->session->userdata('bt_switch');
        
        $ret['circuit_week_start'] = $this->session->userdata('circuit_week_start');
        $ret['circuit_week_end'] = $this->session->userdata('circuit_week_end');
        
        return $ret;
	}

	function update($settings) {
	    //print_r($settings);
	    $this->session->set_userdata($settings);
	    
	    return ;
	}
}