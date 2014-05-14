<?php

if(!defined('BASEPATH'))
    exit('No direct script access allowed');

class Information extends MY_Controller {
    
    private  $data = array();
    
    public function __construct() {
        parent::__construct();       
        $this->checkLoggedIn();
        
    }
    public function index(){
        $this->config->load('information');
        $allPages = $this->config->item('pages');
        $this->load->model('locations_model');
        $user_locs = $this->locations_model->getLocationsByCompanyId($this->session->userdata('company_id')); 

        $locationPages = array();
        foreach ($user_locs as $loc)
        {
           if (isset($allPages[$loc->location_id])&&!empty($allPages[$loc->location_id]))
           {
                $locationsPages[$loc->location_id] = $allPages[$loc->location_id];                
           }
        }
        foreach ($user_locs as $key=>$val)
        {
            $this->data['user_locs_names'][$val->id] = $val->name;
        }
        $this->data['informationsPages'] = array();
        
        foreach ($locationsPages as $key=>$val)
        {
            foreach($val as $title=>$file)
            {
                $pagefilePath = FCPATH.'application/views/merchant/information/pages/'.$key.'/'.$file.'.php';               
                if(!file_exists($pagefilePath)){   
                    continue;
                }
                $this->data['informationsPages'][$key][$title] = $file;
            }
        }
           
        $this->viewData['header'] = $this->load->view('merchant/main/header', $this->data, true);
        $this->viewData['menu'] = $this->load->view('merchant/main/menu', $this->data, true);
        $this->viewData['sidebar'] = $this->load->view('merchant/main/sidebar', $this->data, true);
        $this->viewData['message'] = $this->load->view('merchant/main/message', $this->data, true);
        $this->viewData['content'] = $this->load->view('merchant/information/information_list', $this->data, true);
        $this->viewData['footer'] = $this->load->view('merchant/main/footer', $this->data, true);
        $this->load->view('merchant/main/content', $this->viewData);
    }
    public function page($pageName = null,$loc_id=null){
        
        if(empty($pageName)){
            $this->messages->add("This location has no information", "error");
            redirect('/merchant/information');
        }
        
        $pageViewPath = 'merchant/information/pages/'.$loc_id.'/'.$pageName;
        $pagefilePath = FCPATH.'application/views/merchant/information/pages/'.$loc_id.'/'.$pageName.'.php';
        if(!file_exists($pagefilePath)){
            //$this->messages->add("File not exists", "error");            
            redirect('/merchant/information');
        }
        
        $this->viewData['header'] = $this->load->view('merchant/main/header', $this->data, true);
        $this->viewData['menu'] = $this->load->view('merchant/main/menu', $this->data, true);
        $this->viewData['sidebar'] = $this->load->view('merchant/main/sidebar', $this->data, true);
        $this->viewData['message'] = $this->load->view('merchant/main/message', $this->data, true);
        $this->viewData['content'] = $this->load->view($pageViewPath, $this->data, true);
        $this->viewData['footer'] = $this->load->view('merchant/main/footer', $this->data, true);
        $this->load->view('merchant/main/content', $this->viewData);
    }
}