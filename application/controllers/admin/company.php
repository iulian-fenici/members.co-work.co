<?php

if(!defined('BASEPATH'))
    exit('No direct script access allowed');

class Company extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->checkAdminLoggedIn();
        $this->load->model(array('users_model','ion_auth_model','companies_model','stats_model','bookings_model','locations_model'));
        $this->load->library(array('form_validation', 'ion_auth'));
        $this->load->helper(array('upload_helper','dates_helper'));
        
    }
    
    public function companies_list()
    {
        $this->load->library('pagination');
        $page = (int) $this->input->get('page');
        $this->load->config('pagination', true);       
        $getArr = $this->input->get();
        $configPagination = $this->config->item('pagination_default', 'pagination');
        
        $this->data['companies'] = $this->companies_model->getCompaniesList($getArr, array($this->input->get('order', false) => $this->input->get('direction', false)), $configPagination['per_page'], $page);
        $this->load->config('pagination', true);

        $configPagination['base_url'] = $this->utility->build_filter_href('/admin/company/companies_list', array('page' => null));
        $configPagination['total_rows'] = $this->companies_model->getCountCompanies($getArr);
        //var_dump($configPagination['total_rows']);die();
        $this->pagination->initialize($configPagination);
        $this->data['pagination'] = $this->pagination->create_links();
        
        $this->viewData['header'] = $this->load->view('admin/main/header', $this->data, true);
        $this->viewData['menu'] = $this->load->view('admin/main/menu', $this->data, true);
        $this->viewData['sidebar'] = $this->load->view('admin/main/sidebar', $this->data, true);
        $this->viewData['message'] = $this->load->view('admin/main/message', $this->data, true);
        $this->viewData['content'] = $this->load->view('admin/companies/companies_list', $this->data, true);
        $this->viewData['footer'] = $this->load->view('admin/main/footer', $this->data, true);
        $this->load->view('admin/main/content', $this->viewData);
    }
    public function view_company($companyId)
    {
        $this->data = array();
        $this->data['companyData'] = $this->companies_model->get($companyId);
        if(empty($this->data['companyData'])){
            $this->messages->add("Company not found", "error");
            redirect('/admin/company/companies_list');
        }
        
        $this->data['companyData']->stats = $this->stats_model->getCompanyStatisticInfo($companyId);
        
        $this->data['companyData']->bookings = $this->bookings_model->getCompanyBookings($companyId);
        
        $this->viewData['header'] = $this->load->view('admin/main/header', $this->data, true);
        $this->viewData['menu'] = $this->load->view('admin/main/menu', $this->data, true);
        $this->viewData['sidebar'] = $this->load->view('admin/main/sidebar', $this->data, true);
        $this->viewData['message'] = $this->load->view('admin/main/message', $this->data, true);
        $this->viewData['content'] = $this->load->view('admin/companies/view_company', $this->data, true);
        $this->viewData['footer'] = $this->load->view('admin/main/footer', $this->data, true);
        $this->load->view('admin/main/content', $this->viewData);
    }
    public function edit_company($companyId = 0)
    {
        $this->data = array();
        
        if ($companyId)
        {
            $this->data['companyData'] = $this->companies_model->as_array()->get($companyId);
            $this->data['locationsArr'] = $this->companies_model->as_array()->getCompanyLocations($companyId);
            if(empty($this->data['companyData'])){
                $this->messages->add("Company data not found", "error");
                redirect('/admin/company/companies_list');
            }
        }else{
            $this->data['locationsArr'] = $this->locations_model->as_array()->get_all();
        }

        $this->_setValidationRules();

        if($this->input->post('save')){
            
            if($this->form_validation->run() == true){
                $this->_getDataFromForm();   
               
                if($this->_saveData($companyId))
                    $this->messages->add("Company edited successfully", "success");
                redirect('/admin/company/companies_list');
            }
            else {
                //validation errors
            }
        }
        //$locationsList = $this->locations_model->getLocationsListDD();
        //$this->data['locationsList'] = form_dropdown('location_id', $locationsList, set_value('location_id',isset($this->data['companyData']['location_id'])&&!empty($this->data['companyData']['location_id']) ? $this->data['companyData']['location_id']:0));
       
        
        $this->data['id'] = isset($this->data['companyData']) && !empty($this->data['companyData'])? $this->data['companyData']['id']:$companyId;
        
        //view merchant
        $this->viewData['header'] = $this->load->view('admin/main/header', $this->data, true);
        $this->viewData['menu'] = $this->load->view('admin/main/menu', $this->data, true);
        $this->viewData['sidebar'] = $this->load->view('admin/main/sidebar', $this->data, true);
        $this->viewData['message'] = $this->load->view('admin/main/message', $this->data, true);
        $this->viewData['content'] = $this->load->view('admin/companies/edit_company', $this->data, true);
        $this->viewData['footer'] = $this->load->view('admin/main/footer', $this->data, true);
        $this->load->view('admin/main/content', $this->viewData);
    }
    
    public function delete_company($companyId)
    {
        $this->data = array();
        if ($companyId)
        {
            $this->data['companyData'] = (array)$this->companies_model->get($companyId);
            if(empty($this->data['companyData'])){
                $this->messages->add("Company data not found", "error");
                redirect('/admin/company/companies_list');
            }
            
            $res = $this->companies_model->soft_delete($companyId);
            if ($res)
            {   
                $this->_deactivateCompanyUsers($companyId);
                
                $this->messages->add("Company deleted successfully", "success");
            }
            else
                $this->messages->add("Error on delete company", "error");
            redirect('/admin/company/companies_list');
        }

    }
    public function _deactivateCompanyUsers($companyId){
        $companyUsersArr = $this->users_model->getCompanyUsers($companyId);
        if(!empty($companyUsersArr)){
            foreach($companyUsersArr as $user){
                $this->ion_auth_model->deactivate($user['id']);
            }
        }
    }
    
 
    private function _setValidationRules() {
        //Personal data
        $this->form_validation->set_rules('name', 'Company name', 'required|xss_clean|trim');
        $this->form_validation->set_rules('description', 'Company description', 'xss_clean|trim');
        $this->form_validation->set_rules('phone', 'Phone', 'xss_clean|trim|numeric');
        $this->form_validation->set_rules('email', 'Email', 'xss_clean|trim|valid_email');
        $this->form_validation->set_rules('site_url', 'Web Site Url', 'xss_clean|trim');
//        $this->form_validation->set_rules('location_id', 'Location', 'xss_clean|trim|required');
        $this->form_validation->set_rules('locations[]', 'Locations', 'xss_clean|trim|required');
      }
      
    private function _getDataFromForm() {
        $this->data['name'] = $this->input->post('name', true);
        $this->data['description'] = $this->input->post('description', true);
        $this->data['phone'] = $this->input->post('phone', true);
        $this->data['email'] = $this->input->post('email', true);
        $this->data['site_url'] = $this->input->post('site_url', true);
        $this->data['uploaded-photo'] = $this->input->post('uploaded-photo', true);
//        $this->data['location_id'] = $this->input->post('location_id', true);
        $this->data['locations'] = $this->input->post('locations', true);
    }

    private function _saveData($companyId = 0) {
        
        $updateCompanyData = array(
            'name' => $this->data['name'],
            'building_id' => $this->buildingId,
            'phone' => $this->data['phone'],
            'description' => $this->data['description'],
            'email' => $this->data['email'],
            'site_url' => $this->data['site_url'],
//            'location_id' => $this->data['location_id'],
        );
       
        if(isset($this->data['uploaded-photo']) && !empty($this->data['uploaded-photo'])){
            $result = prepareImages($this->data['uploaded-photo'], 'company');
            $updateCompanyData['thumb_abs_path'] = $result['new_abs_thumb_path'];
            $updateCompanyData['thumb_rel_path'] = $result['new_rel_thumb_path'];
            $updateCompanyData['thumb_name'] = $result['file_name'];
            $updateCompanyData['big_abs_path'] = $result['new_abs_path'];
            $updateCompanyData['big_rel_path'] = $result['new_rel_path'];
            $updateCompanyData['big_name'] = $result['file_name'];
        }
        
        if ($companyId)
            $resultUpdate = $this->companies_model->update($companyId, $updateCompanyData);
        else {
            $resultUpdate = $companyId = $this->companies_model->insert($updateCompanyData);
        }
        
        if(isset($this->data['locations']) && !empty($this->data['locations'])){
            $this->companies_model->deleteCompaniesRef($companyId);
            foreach($this->data['locations'] as $locationId){
                $insertCompaniesRefData[] = array(
                    'company_id'=>$companyId,
                    'location_id'=>$locationId,
                );
            }   
            $this->companies_model->addCompaniesRef($insertCompaniesRefData);
        }
        
        
        return $resultUpdate;
    }
    public function delete_company_photo(){
        
        if($this->input->post('companyId')){
            $companyId = $this->input->post('companyId');
            $this->data = array();
            $this->data['companyData'] = $this->companies_model->get($companyId);

            if(!empty($this->data['companyData'])){
                //unlink files
                if(!empty($this->data['companyData']->thumb_abs_path) && !empty($this->data['companyData']->thumb_name) && file_exists($this->data['companyData']->thumb_abs_path.$this->data['companyData']->thumb_name)){
                    unlink($this->data['companyData']->thumb_abs_path.$this->data['companyData']->thumb_name);
                }
                if(!empty($this->data['companyData']->big_abs_path) && !empty($this->data['companyData']->big_name) && file_exists($this->data['companyData']->big_abs_path.$this->data['companyData']->big_name)){
                    unlink($this->data['companyData']->big_abs_path.$this->data['companyData']->big_name);
                }
                //remove from db
                $updateCompanyData['thumb_abs_path'] = '';
                $updateCompanyData['thumb_rel_path'] = '';
                $updateCompanyData['thumb_name'] = '';
                $updateCompanyData['big_abs_path'] = '';
                $updateCompanyData['big_rel_path'] = '';
                $updateCompanyData['big_name'] = '';
                $this->companies_model->update($this->data['companyData']->id, $updateCompanyData);
                
                $this->load->view('admin/echodata',array('data'=>array('success'=>'Photo deleted successfully')));
            }else{
                $this->load->view('admin/echodata',array('data'=>array('error'=>'Photo deleted unsuccessfully')));
            }
        }
    }   
}