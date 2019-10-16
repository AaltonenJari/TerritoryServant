<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Maintenance_controller extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        
        // Load the maintenance model to make it available
        // to *all* of the controller's actions
        $this->load->model('Maintenance_model');
    }
    
    public function maintain($sort_by = 'alue_code', $sort_order = 'asc', $filter = '') 
    {
        $data['display_fields'] = array(
            'alue_code'		=> 'numero',
            'alue_detail'	=> 'alue_nimi',
            'alue_location'	=> 'lisätieto',
            'alue_taloudet'	=> 'koko'
        );
        //Korjaa ääkköset takaisin
        $filter = urldecode($filter);
        
        $results = $this->Maintenance_model->get_all_entries($data['display_fields'], $sort_by, $sort_order);

        $data['alueet'] = $results['rows'];
        $data['num_results'] = $results['num_rows'];
        
        //Parameters back to view page
        $data['sort_by'] = $sort_by;
        $data['sort_order'] = $sort_order;
        $data['filter'] = $filter;
        
        $this->load->view('maintenance_view', $data);
    }
    
    public function update($alue_numero)
    {
        $columns = array(
            'alue_code',
            'alue_detail',
            'alue_location',
            'alue_taloudet'	
        );
        
    }
        
}