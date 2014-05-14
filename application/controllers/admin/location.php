<?php

if(!defined('BASEPATH'))
    exit('No direct script access allowed');

class Location extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->checkAdminLoggedIn();
        $this->load->model(array('locations_model'));
        $this->load->library(array('form_validation', 'ion_auth'));
    }
    
    public function locations_list()
    {
        $this->load->library('pagination');
        $page = (int) $this->input->get('page');
        $this->load->config('pagination', true);       
        $getArr = $this->input->get();
        $configPagination = $this->config->item('pagination_default', 'pagination');
        
        $this->data['locations'] = $this->locations_model->getLocationsList($getArr, array($this->input->get('order', false) => $this->input->get('direction', false)), $configPagination['per_page'], $page);
        
        $this->load->config('pagination', true);

        $configPagination['base_url'] = $this->utility->build_filter_href('/admin/location/locations_list', array('page' => null));
        $configPagination['total_rows'] = $this->locations_model->getCountLocations($getArr);
        //var_dump($configPagination['total_rows']);die();
        $this->pagination->initialize($configPagination);
        $this->data['pagination'] = $this->pagination->create_links();
        
        $this->viewData['header'] = $this->load->view('admin/main/header', $this->data, true);
        $this->viewData['menu'] = $this->load->view('admin/main/menu', $this->data, true);
        $this->viewData['sidebar'] = $this->load->view('admin/main/sidebar', $this->data, true);
        $this->viewData['message'] = $this->load->view('admin/main/message', $this->data, true);
        $this->viewData['content'] = $this->load->view('admin/locations/locations_list', $this->data, true);
        $this->viewData['footer'] = $this->load->view('admin/main/footer', $this->data, true);
        $this->load->view('admin/main/content', $this->viewData);
    }
    
    public function edit_location($locationId = 0)
    {
        $this->data = array();
        
        if ($locationId)
        {
            $this->data['locationData'] = (array)$this->locations_model->get($locationId);
            if(empty($this->data['locationData'])){
                $this->messages->add("Location data not found", "error");
                redirect('/admin/location/locations_list');
            }
        }

        $this->_setValidationRules();

        if($this->input->post('save')){
            
            if($this->form_validation->run() == true){
                $this->_getDataFromForm();   
               
                if($this->_saveData($locationId))
                    $this->messages->add("Location edited successfully", "success");
                redirect('/admin/location/locations_list');
            }
            else {
                //validation errors
            }
        }
        $this->data['id'] = isset($this->data['locationData']) && !empty($this->data['locationData'])? $this->data['locationData']['id']:$locationId;
        //view merchant
        $this->viewData['header'] = $this->load->view('admin/main/header', $this->data, true);
        $this->viewData['menu'] = $this->load->view('admin/main/menu', $this->data, true);
        $this->viewData['sidebar'] = $this->load->view('admin/main/sidebar', $this->data, true);
        $this->viewData['message'] = $this->load->view('admin/main/message', $this->data, true);
        $this->viewData['content'] = $this->load->view('admin/locations/edit_location', $this->data, true);
        $this->viewData['footer'] = $this->load->view('admin/main/footer', $this->data, true);
        $this->load->view('admin/main/content', $this->viewData);
    }
    
    public function delete_location($locationId)
    {
        $this->data = array();
        if ($locationId)
        {
            $this->data['locationData'] = (array)$this->locations_model->get($locationId);
            if(empty($this->data['locationData'])){
                $this->messages->add("Location data not found", "error");
                redirect('/admin/location/locations_list');
            }
            
            $res = $this->locations_model->delete($locationId);
            if ($res)
            {
                $this->messages->add("Location deleted successfully", "success");
            }
            else
                $this->messages->add("Error on delete location", "error");
            redirect('/admin/location/locations_list');
        }

    }
 
    private function _setValidationRules() {
        //Personal data
        $this->form_validation->set_rules('name', 'Location name', 'required|xss_clean|trim');
        $this->form_validation->set_rules('description', 'Location description', 'required|xss_clean|trim');
      }
      
    private function _getDataFromForm() {
        $this->data['name'] = $this->input->post('name', true);
        $this->data['description'] = $this->input->post('description', true);
    }

    private function _saveData($locationId = 0) {
        
        $updateLocationData = array(
            'name' => $this->data['name'],
            'building_id' => $this->buildingId,
            'description' => $this->data['description'],
        );
        if ($locationId)
            $resultUpdate = $this->locations_model->update($locationId, $updateLocationData);
        else {
            $resultUpdate = $this->locations_model->insert($updateLocationData);
        }
        return $resultUpdate;
    }
}