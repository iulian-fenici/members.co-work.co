<?php

if(!defined('BASEPATH'))
    exit('No direct script access allowed');

class Company extends MY_Controller {
    
    var $data = array();
    
    public function __construct() {
        //var_dump($this->session->all_userdata());
        parent::__construct();       
        $this->checkLoggedIn();
        $this->load->model(array('companies_model','stats_model','bookings_model','users_model','locations_model'));
    }
    
    public function companies_list(){
        $userLocationsIds = $this->locations_model->getLocationsVariableByCompanyId($this->session->userdata('company_id'),'id');
        $this->data['companiesArr'] = $this->companies_model->getCompaniesListByLocationId($userLocationsIds);
        
        $this->viewData['header'] = $this->load->view('merchant/main/header', $this->data, true);
        $this->viewData['menu'] = $this->load->view('merchant/main/menu', $this->data, true);
        $this->viewData['sidebar'] = $this->load->view('merchant/main/sidebar', $this->data, true);
        $this->viewData['message'] = $this->load->view('merchant/main/message', $this->data, true);
        $this->viewData['content'] = $this->load->view('merchant/companies/companies_list', $this->data, true);
        $this->viewData['footer'] = $this->load->view('merchant/main/footer', $this->data, true);
        $this->load->view('merchant/main/content', $this->viewData);
    }
    public function company_view($companyId = 0){
        settype($companyId, 'integer');
        
        $this->data['companyData'] = $this->companies_model->getCompaniesById($companyId);
        $this->data['companyData']['stats'] = $this->stats_model->getCompanyStatisticInfo($companyId);        
        $this->data['companyData']['bookings'] = $this->bookings_model->getCompanyBookings($companyId);
        $this->data['companyData']['users'] = $this->users_model->getCompanyUsers($companyId);
         
        $this->viewData['header'] = $this->load->view('merchant/main/header', $this->data, true);
        $this->viewData['menu'] = $this->load->view('merchant/main/menu', $this->data, true);
        $this->viewData['sidebar'] = $this->load->view('merchant/main/sidebar', $this->data, true);
        $this->viewData['message'] = $this->load->view('merchant/main/message', $this->data, true);
        $this->viewData['content'] = $this->load->view('merchant/companies/companie_view', $this->data, true);
        $this->viewData['footer'] = $this->load->view('merchant/main/footer', $this->data, true);
        $this->load->view('merchant/main/content', $this->viewData);
    }
    
}