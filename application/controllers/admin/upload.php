<?php

if(!defined('BASEPATH'))
    exit('No direct script access allowed');

class Upload extends MY_Controller {
        
    var $config = array();
    public function __construct() {
        parent::__construct();       
    }
    
    public function add_user_photo(){
        $this->load->library('UploadHandler');
        
    }
    public function add_location_photo(){
        $this->load->library('UploadHandler');
        
    }
}
