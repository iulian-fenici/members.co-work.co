<?php

if(!defined('BASEPATH'))
    exit('No direct script access allowed');

class Admin extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->checkAdminLoggedIn();
        $this->load->model(array('users_model', 'companies_model','bookings_model','stats_model','ion_auth_model'));
        $this->load->library(array('form_validation', 'ion_auth'));
        $this->load->helper(array('upload_helper','dates_helper'));
    }
    
   
    public function admins_list()
    {
        $this->load->library('pagination');
        $page = (int) $this->input->get('page');
        $this->load->config('pagination', true);       
        $getArr = $this->input->get();
        $configPagination = $this->config->item('pagination_default', 'pagination');
        
        $this->data['users'] = $this->users_model->getAdminList($getArr, array($this->input->get('order', false) => $this->input->get('direction', false)), $configPagination['per_page'], $page);

        $this->load->config('pagination', true);

        $configPagination['base_url'] = $this->utility->build_filter_href('/admin/admin/admins_list', array('page' => null));
        $configPagination['total_rows'] = $this->users_model->getCountAdmins($getArr);
        //var_dump($configPagination['total_rows']);die();
        $this->pagination->initialize($configPagination);
        $this->data['pagination'] = $this->pagination->create_links();
                
        $this->viewData['header'] = $this->load->view('admin/main/header', $this->data, true);
        $this->viewData['menu'] = $this->load->view('admin/main/menu', $this->data, true);
        $this->viewData['sidebar'] = $this->load->view('admin/main/sidebar', $this->data, true);
        $this->viewData['message'] = $this->load->view('admin/main/message', $this->data, true);
        $this->viewData['content'] = $this->load->view('admin/admins/admins_list', $this->data, true);
        $this->viewData['footer'] = $this->load->view('admin/main/footer', $this->data, true);
        $this->load->view('admin/main/content', $this->viewData);
    }

    public function delete_admin($userId)
    {
        $this->load->model(array('bookings_model', 'notifications_model','announcements_model'));
        $this->data = array();
        $this->data['userData'] = $this->users_model->get($userId);
        if($this->session->userdata('user_id') == $userId){
            $this->messages->add("This admin is current logged in", "error");
            redirect('/admin/admin/admins_list');
        }
        // user_groups
        $this->users_model->deleteUserFromGroupByUserId($userId);
        $this->notifications_model->deleteNotificationsByUserId($userId);
        $this->announcements_model->deleteAnnouncementsRefByUserId($userId);
        $res = $this->users_model->deleteUser($userId);
        if ($res)
        {
            // delete photo
            if(!empty($this->data['userData']->thumb_abs_path) && !empty($this->data['userData']->thumb_name) && file_exists($this->data['userData']->thumb_abs_path.$this->data['userData']->thumb_name)){
                unlink($this->data['userData']->thumb_abs_path.$this->data['userData']->thumb_name);
            }
            if(!empty($this->data['userData']->big_abs_path) && !empty($this->data['userData']->big_name) && file_exists($this->data['userData']->big_abs_path.$this->data['userData']->big_name)){
                unlink($this->data['userData']->big_abs_path.$this->data['userData']->big_name);
            }
            $this->messages->add("Admin deleted successfully", "success");
        }
        else
            $this->messages->add("Error on delete admin", "error");
        redirect('/admin/admin/admins_list');
    }
    
    public function edit_admin($userId = 0)
    {
        $this->data = array();
        
        if ($userId)
        {
            if ($this->users_model->isFirstEntered($this->session->userdata('email')))
            {        
                $this->firstEntered = true;
            }
            else {
                $this->firstEntered = false;
            }
            //$this->data['userData'] = $this->users_model->get($userId);
            $this->data['userData'] = $this->users_model->getUserDataByUserId($userId);
            //var_dump($this->data['userData']);
            if(empty($this->data['userData'])){
                $this->messages->add("User data not found", "error");
            redirect('/admin/dashboard/index');
            }
        }

        $this->_setValidationRules($userId);

        if($this->input->post('save')){
            
            if($this->form_validation->run() == true){
                $this->_getDataFromForm();   
                if($this->_saveData($userId))
                    $this->messages->add("Admin edited successfully", "success");
                if ($this->firstEntered)
                    redirect('/admin/dashboard/index');
                else
                    redirect('/admin/admin/admins_list');
            }
            else {
                //validation errors
            }
        }
        $this->data['id'] = isset($this->data['userData']) && !empty($this->data['userData'])? $this->data['userData']['id']:$userId;
        //view merchant
        $this->viewData['header'] = $this->load->view('admin/main/header', $this->data, true);
        $this->viewData['menu'] = $this->load->view('admin/main/menu', $this->data, true);
        $this->viewData['sidebar'] = $this->load->view('admin/main/sidebar', $this->data, true);
        $this->viewData['message'] = $this->load->view('admin/main/message', $this->data, true);
        $this->viewData['content'] = $this->load->view('admin/admins/edit_admin', $this->data, true);
        $this->viewData['footer'] = $this->load->view('admin/main/footer', $this->data, true);
        $this->load->view('admin/main/content', $this->viewData);
    }
     private function _setValidationRules($userId) {
        //Personal data
        $this->form_validation->set_rules('first_name', 'First Name', 'required|xss_clean|trim');
        $this->form_validation->set_rules('last_name', 'Last Name', 'required|xss_clean|trim');
        
        $this->form_validation->set_rules('phone', 'Phone', 'xss_clean|trim|numeric');
        
        if ($userId)
        {
            $this->form_validation->set_rules('email', 'Email Address', 'valid_email|required|xss_clean|trim|is_unique[users.email.id.'.$userId.']');
            if (!$this->firstEntered)
            {
                $this->form_validation->set_rules('password', 'Password', 'min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|xss_clean');
                $this->form_validation->set_rules('password_confirm', 'Password Confirmation', 'xss_clean');
            }
            else {
                $this->form_validation->set_rules('password', 'Password', 'min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[password_confirm]|xss_clean|required');
                $this->form_validation->set_rules('password_confirm', 'Password Confirmation', 'xss_clean|required');                
            }
        }
        else {
            $this->form_validation->set_rules('email', 'Email Address', 'valid_email|required|xss_clean|trim|is_unique[users.email]');
            $this->form_validation->set_rules('password', 'Password', 'min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[password_confirm]|xss_clean|required');
            $this->form_validation->set_rules('password_confirm', 'Password Confirmation', 'xss_clean|required');
        }
    }

    private function _getDataFromForm() {
        $this->data['first_name'] = $this->input->post('first_name', true);
        $this->data['last_name'] = $this->input->post('last_name', true);
        $this->data['email'] = $this->input->post('email', true);
        $this->data['phone'] = $this->input->post('phone', true);
        $this->data['password'] = $this->input->post('password', true);
    }

    private function _saveData($userId = 0) {
        $updateUserData = array(
            'first_name' => $this->data['first_name'],
            'last_name' => $this->data['last_name'],
            'phone' => $this->data['phone'],   
        );
        if(!empty($this->data['password'])){
            $updateUserData['password'] = $this->data['password'];
        }
        if ($userId)
        {
            $this->load->model('users_model');
            if(!empty($this->data['password'])){
                $updateUserData['password'] = $this->ion_auth_model->hash_password($this->data['password'], $this->data['userData']->salt);
                if ($this->firstEntered)
                    $this->users_model->resetFirstEntered($userId);
            }
            if(!empty($this->data['email'])){
                $updateUserData['email'] = $this->data['email'];
            }
            $resultUpdate = $this->users_model->update($userId, $updateUserData);
        }
        else {
            if(!empty($this->data['password'])){
                $updateUserData['password'] =$this->data['password'];
            }

            $resultUpdate = $this->ion_auth_model->register($this->data['first_name']. ' '. $this->data['last_name'], $updateUserData['password'], $this->data['email'], $updateUserData, array(1));
            
            //$this->ion_auth_model->add_to_group(2, $resultUpdate);
        }
        return $resultUpdate;
    }
    

}

