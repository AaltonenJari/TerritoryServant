<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Settings_controller extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        
        // Load the maintenance model to make it available
        // to *all* of the controller's actions
        $this->load->model('Settings_model');
    }
    
    public function settings() 
    {
        $results = $this->Settings_model->get_settings();
        
        //Setting parameters to view page
        $data['name_presentation'] = $results['name_presentation'];
        
        $data['event_date_order'] = $results['event_date_order'];
        $data['archive_time'] = $results['archive_time'];
        
        $data['circuit_week_start'] = $results['circuit_week_start'];
        $data['circuit_week_end'] = $results['circuit_week_end'];
        
        $this->load->view('settings_view', $data);
    }
       
    public function check_settings()
    {
        $action = $this->input->post('action');
        if ($action == 'Päivitä') {
            $this->update_settings();
            //Palataan päänäytölle
            $main_url = 'Location: ' . base_url("index.php/Territory_controller/display");
            header($main_url);
        }
        if ($action == 'Paluu') {
            //Palataan päänäytölle
            $main_url = 'Location: ' . base_url("index.php/Territory_controller/display");
            header($main_url);
        }
        
        return;
    }
    
    public function update_settings()
    {
        //note that the keys are the session->userdata keys defined in navbar.php file.
        $settings_data = array(
            'event_date_order' => $this->input->post('eventOrderOld'),
            'archive_time' => $this->input->post('archiveYearsOld'),
            'name_presentation'  => $this->input->post('namePresentationOld'),
            'circuit_week_start' => $this->input->post('kvviikko_alkaa'),
            'circuit_week_end' => $this->input->post('kvviikko_loppuu')
        );
        
        $this->Settings_model->update($settings_data);
        return;
    }
    
}