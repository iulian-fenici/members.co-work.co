<?php

if(!defined('BASEPATH'))
    exit('No direct script access allowed');

class Profile extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->checkLoggedIn();
        $this->load->model(array('users_model','ion_auth_model','stats_model'));
        $this->load->library(array('form_validation', 'ion_auth'));
        $this->load->helper(array('upload_helper'));
    }

    public function view_profile($userId = false) {
        $this->data = array();
        settype($userId,'integer');
        if(!empty($userId)){
            $this->data['userData'] = $this->users_model->get($userId);
        }else{
            $this->data['userData'] = $this->users_model->get($this->session->userdata('user_id'));
        }
        
        if(empty($this->data['userData'])){
            $this->messages->add("User profile not found", "error");
            redirect('/merchant/dashboard/index');
        }
        
        $this->data['userData']->stats = $this->stats_model->getUserStatisticInfo($this->session->userdata('user_id'));
        
        //view merchant
        $this->viewData['header'] = $this->load->view('merchant/main/header', $this->data, true);
        $this->viewData['menu'] = $this->load->view('merchant/main/menu', $this->data, true);
        $this->viewData['sidebar'] = $this->load->view('merchant/main/sidebar', $this->data, true);
        $this->viewData['message'] = $this->load->view('merchant/main/message', $this->data, true);
        $this->viewData['content'] = $this->load->view('merchant/profile/view_profile', $this->data, true);
        $this->viewData['footer'] = $this->load->view('merchant/main/footer', $this->data, true);
        $this->load->view('merchant/main/content', $this->viewData);
    }

    public function edit_profile() {
        $this->data = array();
        $this->data['userData'] = $this->users_model->get($this->session->userdata('user_id'));
        if(empty($this->data['userData'])){
            $this->messages->add("User profile not found", "error");
            redirect('/merchant/dashboard/index');
        }
        if ($this->users_model->isFirstEntered($this->session->userdata('email')))
        {        
            $this->firstEntered = true;
        }
        else {
            $this->firstEntered = false;
        }
        $this->_setValidationRules();

        if($this->input->post('save')){
            
            if($this->form_validation->run() == true){
                $this->_getDataFromForm();          
                if($this->_saveData())
                    $this->messages->add("Profile edited successfully", "success");
                if ($this->firstEntered)
                    redirect('/admin/dashboard/index');
                else
                    redirect('/merchant/profile/view_profile');
            }
            else {
                //validation errors
            }
        }
        //view merchant
        $this->viewData['header'] = $this->load->view('merchant/main/header', $this->data, true);
        $this->viewData['menu'] = $this->load->view('merchant/main/menu', $this->data, true);
        $this->viewData['sidebar'] = $this->load->view('merchant/main/sidebar', $this->data, true);
        $this->viewData['message'] = $this->load->view('merchant/main/message', $this->data, true);
        $this->viewData['content'] = $this->load->view('merchant/profile/edit_profile', $this->data, true);
        $this->viewData['footer'] = $this->load->view('merchant/main/footer', $this->data, true);
        $this->load->view('merchant/main/content', $this->viewData);
    }

    private function _setValidationRules() {
        //Personal data
        $this->form_validation->set_rules('first_name', 'First Name', 'required|xss_clean|trim');
        $this->form_validation->set_rules('last_name', 'Last Name', 'required|xss_clean|trim');
        $this->form_validation->set_rules('email', 'Email Address', 'valid_email|required|xss_clean|trim');
        $this->form_validation->set_rules('phone', 'Phone', 'xss_clean|trim|numeric');
        
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

    private function _getDataFromForm() {
        $this->data['first_name'] = $this->input->post('first_name', true);
        $this->data['last_name'] = $this->input->post('last_name', true);
        $this->data['email'] = $this->input->post('email', true);
        $this->data['phone'] = $this->input->post('phone', true);
        $this->data['password'] = $this->input->post('password', true);
        $this->data['uploaded-photo'] = $this->input->post('uploaded-photo', true);
    }

    private function _saveData() {
        $updateUserData = array(
            'first_name' => $this->data['first_name'],
            'last_name' => $this->data['last_name'],
            'email' => $this->data['email'],
            'phone' => $this->data['phone'],            
        );
        if(!empty($this->data['password'])){
            $updateUserData['password'] = $this->ion_auth_model->hash_password($this->data['password'], $this->data['userData']->salt);
            if ($this->firstEntered)
                $this->users_model->resetFirstEntered($this->data['userData']->id);
        }
        if(isset($this->data['uploaded-photo']) && !empty($this->data['uploaded-photo'])){
            $result = prepareImages($this->data['uploaded-photo'], 'profile');
            $updateUserData['thumb_abs_path'] = $result['new_abs_thumb_path'];
            $updateUserData['thumb_rel_path'] = $result['new_rel_thumb_path'];
            $updateUserData['thumb_name'] = $result['file_name'];
            $updateUserData['big_abs_path'] = $result['new_abs_path'];
            $updateUserData['big_rel_path'] = $result['new_rel_path'];
            $updateUserData['big_name'] = $result['file_name'];
        }
        $resultUpdate = $this->users_model->update($this->data['userData']->id, $updateUserData);
        return $resultUpdate;
    }
    public function delete_user_photo(){
        
        if($this->input->post('param')){
            $this->data = array();
            $this->data['userData'] = $this->users_model->get($this->session->userdata('user_id'));

            if(!empty($this->data['userData'])){
                //unlink files
                if(!empty($this->data['userData']->thumb_abs_path) && !empty($this->data['userData']->thumb_name) && file_exists($this->data['userData']->thumb_abs_path.$this->data['userData']->thumb_name)){
                    unlink($this->data['userData']->thumb_abs_path.$this->data['userData']->thumb_name);
                }
                if(!empty($this->data['userData']->big_abs_path) && !empty($this->data['userData']->big_name) && file_exists($this->data['userData']->big_abs_path.$this->data['userData']->big_name)){
                    unlink($this->data['userData']->big_abs_path.$this->data['userData']->big_name);
                }
                //remove from db
                $updateUserData['thumb_abs_path'] = '';
                $updateUserData['thumb_rel_path'] = '';
                $updateUserData['thumb_name'] = '';
                $updateUserData['big_abs_path'] = '';
                $updateUserData['big_rel_path'] = '';
                $updateUserData['big_name'] = '';
                $this->users_model->update($this->data['userData']->id, $updateUserData);
                
                $this->load->view('merchant/echodata',array('data'=>array('success'=>'Photo deleted successfully')));
            }else{
                $this->load->view('merchant/echodata',array('data'=>array('error'=>'Photo deleted unsuccessfully')));
            }
        }
    }
}