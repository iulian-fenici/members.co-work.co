<?php

if(!defined('BASEPATH'))
    exit('No direct script access allowed');

class My_Controller extends CI_Controller {

    var $buildingId;
    
    public function __construct() {
        parent::__construct();
        $this->load->config('registration');
        $this->buildingId = $this->config->item('default_building_id');
        $this->_setTimezone($this->config->item('default_timezone'));
    }
    
    private function _setTimezone($timeZone){
        date_default_timezone_set($timeZone);  
        $this->db->query("SET time_zone = ?",array(date('P')));
    }

     public function AuthCheck() {
        if(!$this->ion_auth->logged_in()){
            redirect(base_url() . 'auth', 'refresh');
        }
     }
    
    public function checkLoggedIn() {       
        
        if(!$this->ion_auth->logged_in()){
            $getParameters = $this->input->get();
            if(!empty($getParameters)){
                $this->session->set_userdata(array('getParameters'=>$getParameters));
            }
            redirect(base_url() . 'auth', 'refresh');            
        }else{
            if ($this->ion_auth->is_admin())
            {
                redirect('/admin/dashboard', 'refresh');
            }
        }
    }
    
    
    
    public function checkAdminLoggedIn() {
        if(!$this->ion_auth->logged_in()){
            redirect(base_url() . 'auth', 'refresh');
        }else{
            if (!$this->ion_auth->is_admin())
            {
                redirect('/merchant/dashboard', 'refresh');
            }
        }
    }    
    public function checkAccess($groupsArr) {
        if(!empty($groupsArr)){
            if(!$this->ion_auth->in_group($groupsArr)){
                redirect(base_url() . 'merchant/dashboard/index', 'refresh');
            }
        }
    }
    public function checkOwner($userId) {
        if(!empty($userId)){
            if($userId != $this->session->userdata('user_id')){
                redirect(base_url() . 'merchant/dashboard/index', 'refresh');
            }
        }
    }



}