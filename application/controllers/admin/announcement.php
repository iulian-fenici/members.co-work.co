<?php

if(!defined('BASEPATH'))
    exit('No direct script access allowed');

class Announcement extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->checkAdminLoggedIn();
        $this->load->model(array('announcements_model','locations_model','companies_model','users_model'));
        $this->load->library(array('form_validation', 'ion_auth'));
    }
    
    public function view($id)
    {
        $this->load->model('comments_model');
        $this->data['announcements'] = $this->announcements_model->getAnnouncementsArrByAnnouncementId($id);       
        $this->data['comments'] = $this->comments_model->getComents(array('announcement_id'=>$id));
        $this->data['admin'] = true;
        $this->viewData['header'] = $this->load->view('admin/main/header', $this->data,true);
        $this->viewData['menu'] = $this->load->view('admin/main/menu', $this->data,true);
        $this->viewData['sidebar'] = $this->load->view('admin/main/sidebar', $this->data,true);
        $this->viewData['message'] = $this->load->view('admin/main/message', $this->data,true);
        $this->viewData['content'] = $this->load->view('comments/full_view', $this->data,true);
        $this->viewData['footer'] = $this->load->view('admin/main/footer', $this->data,true);
        $this->load->view('admin/main/content', $this->viewData);
    }
    
    
    public function announcements_list(){
        $this->data = array();
        
        $this->load->library('pagination');
        $page = (int) $this->input->get('page');
        $this->load->config('pagination', true);       
        $getArr = $this->input->get();
        $configPagination = $this->config->item('pagination_default', 'pagination');        
        
        $this->data['announcements'] = $this->announcements_model->getAnnouncementsList($getArr, array($this->input->get('order', false) => $this->input->get('direction', false)), $configPagination['per_page'], $page,1);
        $this->load->config('pagination', true);

        $configPagination['base_url'] = $this->utility->build_filter_href('/admin/announcement/announcements_list', array('page' => null));
        $configPagination['total_rows'] = $this->announcements_model->getCountAnnouncements($getArr, array($this->input->get('order', false) => $this->input->get('direction', false)), 0, 0, 1);

        $this->pagination->initialize($configPagination);
        $this->data['pagination'] = $this->pagination->create_links();
        
        $this->viewData['header'] = $this->load->view('admin/main/header', $this->data, true);
        $this->viewData['menu'] = $this->load->view('admin/main/menu', $this->data, true);
        $this->viewData['sidebar'] = $this->load->view('admin/main/sidebar', $this->data, true);
        $this->viewData['message'] = $this->load->view('admin/main/message', $this->data, true);
        $this->viewData['content'] = $this->load->view('admin/announcements/announcements_list', $this->data, true);
        $this->viewData['footer'] = $this->load->view('admin/main/footer', $this->data, true);
        $this->load->view('admin/main/content', $this->viewData);
    }
    public function add_announcement() {
        $this->data = array();
        
        $this->data['locationsArr'] = $this->locations_model->getLocationsList();
        $this->data['companiesArr'] = $this->companies_model->getCompaniesList();
        $this->data['usersArr'] = $this->users_model->getUserList();

        $this->_setValidationRules();
        if($this->input->post('save')){
            if($this->form_validation->run() == true){
                $this->_getDataFromForm();

                if($this->_saveData()){

                    $this->messages->add("Announcement created successfully", "success");
                    redirect('/admin/announcement/announcements_list');
                }
            }
            else {
                //validation errors
            }
        }
        //view merchant
        $this->viewData['header'] = $this->load->view('admin/main/header', $this->data, true);
        $this->viewData['menu'] = $this->load->view('admin/main/menu', $this->data, true);
        $this->viewData['sidebar'] = $this->load->view('admin/main/sidebar', $this->data, true);
        $this->viewData['message'] = $this->load->view('admin/main/message', $this->data, true);
        $this->viewData['content'] = $this->load->view('admin/announcements/add_announcement', $this->data, true);
        $this->viewData['footer'] = $this->load->view('admin/main/footer', $this->data, true);
        $this->load->view('admin/main/content', $this->viewData);
    }
    public function delete_announcement($announcementId) {
        settype($announcementId,'integer');
        if(empty($announcementId)){
            redirect('/admin/announcement/announcements_list');
        }
        $this->announcements_model->delete($announcementId);
        $this->announcements_model->deleteAnnouncementsRef($announcementId);

        $this->messages->add("Announcement deleted successfully", "success");
        redirect('/admin/announcement/announcements_list');
    }
    public function change_announcement_activity($announcementId,$state) {
        settype($announcementId,'integer');
        settype($state,'integer');
        if(empty($announcementId)){
            redirect('/admin/announcement/announcements_list');
        }        

        $this->announcements_model->update($announcementId,array('active'=>$state));
        
        if($state==1)
            $this->messages->add("Announcement activted successfully", "success");
        else
            $this->messages->add("Announcement deactivated successfully", "success");
        
        redirect('/admin/announcement/announcements_list');
    }
    
    private function _setValidationRules() {
        //Personal data
        $this->form_validation->set_rules('title', 'Subject', 'required|xss_clean|trim');
        $this->form_validation->set_rules('description', 'Description', 'required|xss_clean|trim');
        $this->form_validation->set_rules('dateFrom', 'Date from', 'xss_clean|trim');
        $this->form_validation->set_rules('dateTill', 'Date till', 'xss_clean|trim');
        $this->form_validation->set_rules('locations[]', 'Locations', 'xss_clean|trim');
        $this->form_validation->set_rules('companies[]', 'Companies', 'xss_clean|trim');
        $this->form_validation->set_rules('users[]', 'Users', 'xss_clean|trim');
        $this->form_validation->set_rules('sendEmail', 'Send email', 'xss_clean|trim');
    }

    private function _getDataFromForm() {
        $this->data['title'] = $this->input->post('title', true);
        $this->data['description'] = $this->input->post('description', true);
        $dateArr = explode('/',$this->input->post('dateFrom', true));
        $this->data['dateFrom'] = $dateArr[2].'-'.$dateArr[1].'/'.$dateArr[0];
        $dateArr = explode('/',$this->input->post('dateTill', true));
        $this->data['dateTill'] = $dateArr[2].'-'.$dateArr[1].'/'.$dateArr[0];
        $this->data['locations'] = $this->input->post('locations', true);
        $this->data['companies'] = $this->input->post('companies', true);
        $this->data['users'] = $this->input->post('users', true);
        $this->data['sendEmail'] = $this->input->post('sendEmail', true);
    }

    private function _saveData() {
        
        $insertAnnouncementData = array(
            'title' => $this->data['title'],
            'description' => $this->data['description'],
            'date_from' => $this->data['dateFrom'],
            'date_till' => $this->data['dateTill'],
            'send_email' => (int)$this->data['sendEmail'],
            'admin_announcement'=>1,
            'active' => 1
        );
        $announcementId = $this->announcements_model->insert($insertAnnouncementData);

        if(isset($announcementId) && !empty($announcementId)){

            if(isset($this->data['locations']) && !empty($this->data['locations'])){
                $this->announcements_model->deleteAnnouncementsRef($announcementId,'locations');
                foreach($this->data['locations'] as $locationId){
                    $insertAnnouncementsRefData = array(
                        'announcement_id'=>$announcementId,
                        'row_id'=>$locationId,
                        'table'=>'locations',
                    );
                    $this->announcements_model->addAnnouncementsRef($insertAnnouncementsRefData);
                }                
            }
            if(isset($this->data['companies']) && !empty($this->data['companies'])){
                $this->announcements_model->deleteAnnouncementsRef($announcementId,'companies');
                foreach($this->data['companies'] as $companyId){
                    $insertAnnouncementsRefData = array(
                        'announcement_id'=>$announcementId,
                        'row_id'=>$companyId,
                        'table'=>'companies',
                    );
                    $this->announcements_model->addAnnouncementsRef($insertAnnouncementsRefData);
                } 
            }
            if(isset($this->data['users']) && !empty($this->data['users'])){
                $this->announcements_model->deleteAnnouncementsRef($announcementId,'users');
                foreach($this->data['users'] as $userId){
                    $insertAnnouncementsRefData = array(
                        'announcement_id'=>$announcementId,
                        'row_id'=>$userId,
                        'table'=>'users',
                    );
                    $this->announcements_model->addAnnouncementsRef($insertAnnouncementsRefData);
                } 
            }
            return $announcementId;
        }
        return false;
    }
    public function edit_announcement($id) {
        $this->data = array();
        $this->data['announcementData'] = (array) $this->announcements_model->get_by('id',$id);
        
        $this->data['locationsArr'] = $this->announcements_model->getAnnouncementsLocations($id);
        $userLocationsIds = $this->locations_model->getLocationsVariableByCompanyId($this->session->userdata('company_id'),'id');
        $this->data['companiesArr'] = $this->announcements_model->getAnnouncementsCompanies($id,$userLocationsIds);
        $this->data['usersArr'] = $this->announcements_model->getAnnouncementsUsers($id);
    
        $this->_setValidationRules();
        if($this->input->post('save')){
            if($this->form_validation->run() == true){
                $this->_getDataFromForm();

                if($this->_saveEditData($id)){

                    $this->messages->add("Announcement updated successfully", "success");
                    redirect('/admin/announcement/announcements_list');
                }
            }
            else {
                //validation errors
            }
        }
        //view merchant
        $this->data['id'] = isset($id) && !empty($id)? $id:0;
        $this->viewData['header'] = $this->load->view('admin/main/header', $this->data, true);
        $this->viewData['menu'] = $this->load->view('admin/main/menu', $this->data, true);
        $this->viewData['sidebar'] = $this->load->view('admin/main/sidebar', $this->data, true);
        $this->viewData['message'] = $this->load->view('admin/main/message', $this->data, true);
        $this->viewData['content'] = $this->load->view('admin/announcements/add_announcement', $this->data, true);
        $this->viewData['footer'] = $this->load->view('admin/main/footer', $this->data, true);
        $this->load->view('admin/main/content', $this->viewData);
    }
    
    private function _saveEditData($announcementId) {
        
        $insertAnnouncementData = array(
            'title' => $this->data['title'],
            'description' => $this->data['description'],
            'date_from' => $this->data['dateFrom'],
            'date_till' => $this->data['dateTill'],
            'active' => 1
        );
        $res = $this->announcements_model->update($announcementId, $insertAnnouncementData);
        if(isset($announcementId) && !empty($announcementId)){
            if(isset($this->data['locations']) && !empty($this->data['locations'])){
                $this->announcements_model->deleteAnnouncementsRef($announcementId,'locations');
                foreach($this->data['locations'] as $locationId){
                    $insertAnnouncementsRefData = array(
                        'announcement_id'=>$announcementId,
                        'row_id'=>$locationId,
                        'table'=>'locations',
                    );
                    $this->announcements_model->addAnnouncementsRef($insertAnnouncementsRefData);
                }                
            }
            else {
                $this->announcements_model->deleteAnnouncementsRef($announcementId,'locations');
            }
            if(isset($this->data['companies']) && !empty($this->data['companies'])){
                $this->announcements_model->deleteAnnouncementsRef($announcementId,'companies');
                foreach($this->data['companies'] as $companyId){
                    $insertAnnouncementsRefData = array(
                        'announcement_id'=>$announcementId,
                        'row_id'=>$companyId,
                        'table'=>'companies',
                    );
                    $this->announcements_model->addAnnouncementsRef($insertAnnouncementsRefData);
                } 
            }
            else {
                $this->announcements_model->deleteAnnouncementsRef($announcementId,'companies');
            }
            if(isset($this->data['users']) && !empty($this->data['users'])){
                $this->announcements_model->deleteAnnouncementsRef($announcementId,'users');
                foreach($this->data['users'] as $userId){
                    $insertAnnouncementsRefData = array(
                        'announcement_id'=>$announcementId,
                        'row_id'=>$userId,
                        'table'=>'users',
                    );
                    $this->announcements_model->addAnnouncementsRef($insertAnnouncementsRefData);
                } 
            }
            else {
                $this->announcements_model->deleteAnnouncementsRef($announcementId,'users');
            }
            return $announcementId;
        }
        return false;
    }
}