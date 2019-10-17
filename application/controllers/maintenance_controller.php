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
    
    public function update($terr_nbr)
    {
        $columns = array(
            'alue_code',
            'alue_detail',
            'alue_location',
            'alue_taloudet'	
        );

        $resultrow = $this->Maintenance_model->get_alue_row($columns, $terr_nbr);
        $this->load->view('terr_maintenance', $resultrow);
    }
    
    public function insert()
    {
        $data = array(
            'alue_code' => '',
            'alue_detail' => '',
            'alue_location' => '',
            'alue_taloudet' => ''
        );
        
        $this->load->view('terr_insert', $data);
    }
    
    public function check_territory()
    {
        $action = $this->input->post('action');
        if ($action == 'Päivitä') {
            $this->update_territory();
        }
        if ($action == 'Poista') {
            $this->delete_territory();
        }
        if ($action == 'Lisää') {
            $this->add_territory();
        }
        if ($action == 'Paluu') {
            $this->maintain();
        }
        
       return;
    }

    public function update_territory()
    {
        $data = array(
            'alue_code' => $this->input->post('alue_code'),
            'alue_detail' => $this->input->post('alue_detail'),
            'alue_location' => $this->input->post('alue_location'),
            'alue_taloudet' => $this->input->post('alue_taloudet')
        );
        
        $this->Maintenance_model->update($data, $this->input->post('alue_code'));
        $this->maintain();
    }
    
    public function add_territory()
    {
        $data = array(
            'alue_code' => $this->input->post('alue_code'),
            'alue_detail' => $this->input->post('alue_detail'),
            'alue_location' => $this->input->post('alue_location'),
            'alue_taloudet' => $this->input->post('alue_taloudet')
        );
        
        //Tarkista, onko alue jo kannassa
        $found = $this->Maintenance_model->terrExists($this->input->post('alue_code'));
        if ($found > 0) {
            //Jos löytyi, päivitä se
            $this->Maintenance_model->update($data, $this->input->post('alue_code'));
        } else {
            //muuten lisää uusi
            $this->Maintenance_model->insert($data);
        }
        
        $this->maintain();
    }
        
    public function delete_territory()
    {
        $this->Maintenance_model->delete($this->input->post('alue_code'));
        $this->maintain();
    }
        
}