<?php
class LoginController extends CI_Controller 
{
    public function __construct()
    {
        parent::__construct();
        
        // Load the alue model to make it available
        // to *all* of the controller's actions
        $this->load->model('LoginModel');
        $this->load->model('Settings_model');
    }
    
    public function index() 
    {
        $this->session->unset_userdata('initialized');
        $this->Settings_model->checkInitializeSettings(); //Tsekataan tässä asetukset
        if (empty($this->session->userdata('useSignIn'))) {
            //Kirjautuminen ei ole päällä, käytä oletusta
            $users_data = array(
                'username' => "guest",
                'admin' => "0"
            );
            $this->session->set_userdata($users_data);
            $new_url = base_url("index.php");
            header('Location: ' . $new_url);
            
        } else {
            $this->load->view('login');
        }
    }
    
    public function checkLogin() 
    {
        
        //$this->form_validation->set_rules('username', 'Username', 'required:valid_email');
        //$this->form_validation->set_rules('password', 'Password', 'required:callback_verifyUser');
        
        // set validation rules
        $rules = array(
            array('field' => 'username',
                'label' => 'Username',
                'rules' => 'required') ,
            array('field' => 'password',
                'label' => 'Password',
                'rules' => 'required|callback_verifyUser')
        ) ;
        
        $this->form_validation->set_rules($rules);
        
        if ($this->form_validation->run() == false) {
            $this->load->view('login');
        } else {
            $new_url = "Territory_controller/display_frontpage/";
            redirect($new_url);
        }
        
   }
   
   public function verifyUser() 
   {      
       $name = $this->input->post('username');
       $pass = $this->input->post('password');
    
       if ($this->LoginModel->Login($name, $pass)) {
           $session_data = array(
               'username' => $name
           );
           $this->session->set_userdata($session_data);

           //Haetaan muutkin käyttäjätiedot
           $this->get_user_data ($name);
           
           return true;
       } else {
           $this->form_validation->set_message('verifyUser','Incorrect username or password, please try again.');
           $this->session->set_flashdata('error', 'Invalid Username and Password');
           return false;
       }
   }
   
   public function logout() 
   {
       if (empty($this->session->userdata('useSignIn'))) {
           //Kirjautuminen ei ole käytössä
           $this->session->unset_userdata('username');
           $this->session->unset_userdata('initialized');
           
           $new_url = base_url("index.php");
           header('Location: ' . $new_url);
           
       } else {
           $this->session->unset_userdata('username');
           $this->session->unset_userdata('initialized');
           $this->load->view('login');
       }
   }
   
  private function get_user_data ($username) 
  {
       //Haetaan kirjautuneen käyttäjän tiedot
       $columns = array(
           'user_firstname',
           'user_lastname',
           'user_email',
           'user_admin'
           
       );
       $resultrow = $this->LoginModel->get_user_data($columns, $username);
       
       //Tiedot session-muuttujiin
       $session_data = array(
           'user_firstname' => $resultrow['user_firstname'],
           'user_lastname' => $resultrow['user_lastname'],
           'user_email' => $resultrow['user_email'],
           'admin' => $resultrow['user_admin'],
       );
       $this->session->set_userdata($session_data);
       
   }
}