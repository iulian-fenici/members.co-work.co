<?php

if(!defined('BASEPATH'))
    exit('No direct script access allowed');

class Signup extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library(array('form_validation', 'ion_auth'));
        $this->load->helper('registration');
        $this->load->model(array('ion_auth_model', 'companies_model'));
        $this->load->config('registration');
    }

    public function index() {
        $this->data = array();
        $encodedCompanyId = $this->input->get('id', true);
        
        if($encodedCompanyId)
        {
            $decoded = base64_decode($encodedCompanyId);
            $str2 = str_split($decoded, 15);
            $this->data['company_id'] = $str2[1];
            if(!$this->data['company_id']){
                $this->messages->add('No valid or not set company Id', "error");
            }
            if($this->data['company_id']){
                settype($this->data['company_id'], 'integer');
            }
            $this->data['company'] = $this->companies_model->getCompanyNameById( $this->data['company_id']);
        }
        else {
           
        }
        $this->data['group'] = 'user';

        $this->_setValidationRules();
        
       

        if($this->input->post('signup')){
            if($this->form_validation->run() == true){
                $this->_getDataFromForm();
                if (!isset($this->data['company']))
                    $this->data['company'] = $this->companies_model->getCompanyNameById( $this->data['company_id']);

                $this->newUserId = register($this->data);
                if($this->newUserId){
                    $this->ion_auth->login($this->data['email'], $this->data['password']);
                    $this->messages->add("Thank you for registering", "success");
                    redirect('/merchant/dashboard');
                }
                else {
                    $this->messages->add($this->ion_auth->errors(), "error");
                }
            }
            else {
                //var_dump(validation_errors());
                //validation errors
                $this->_getDataFromForm();
                $this->data['errors'] = true;
                if (!isset($this->data['company']))
                    $this->data['company'] = $this->companies_model->getCompanyNameById( $this->data['company_id']);

            }
        }

//        $this->viewData['header'] = $this->load->view('merchant/main/header', $this->data, true);
//        $this->viewData['menu'] = $this->load->view('merchant/main/registration_menu', $this->data, true);
//        $this->viewData['sidebar'] = $this->load->view('merchant/main/registration_sidebar', $this->data, true);
//        $this->viewData['message'] = $this->load->view('merchant/main/message', $this->data, true);
//        $this->viewData['content'] = $this->load->view('signup/signup1', $this->data, true);
//        $this->viewData['footer'] = $this->load->view('merchant/main/footer', $this->data, true);
//        $this->load->view('merchant/main/content', $this->viewData);
            $this->viewData['header'] = $this->load->view('auth/main/header', $this->data,true);
            $this->viewData['content'] = $this->load->view('signup/signup', $this->data,true);
            $this->viewData['footer'] = $this->load->view('auth/main/footer', $this->data,true);
            $this->load->view('auth/main/content', $this->viewData);
    }

    private function _setValidationRules() {
        //Personal data
        $this->form_validation->set_rules('first_name', 'First Name', 'required|xss_clean|trim');
        $this->form_validation->set_rules('last_name', 'Last Name', 'required|xss_clean|trim');
        $this->form_validation->set_rules('email', 'Email Address', 'valid_email|required|xss_clean|trim');
//        $this->form_validation->set_rules('company', 'Company', 'required|xss_clean|trim');
        $this->form_validation->set_rules('company_id', 'Company', 'required|xss_clean|trim');
        $this->form_validation->set_rules('phone', 'Phone', 'required|xss_clean|trim|numeric');
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[password_confirm]|xss_clean');
        $this->form_validation->set_rules('password_confirm', 'Password Confirmation', 'required|xss_clean');
    }

    private function _getDataFromForm() {
        $this->data['first_name'] = $this->input->post('first_name', true);
        $this->data['last_name'] = $this->input->post('last_name', true);
        $this->data['email'] = $this->input->post('email', true);
//        $this->data['company'] = $this->input->post('company', true);
        $this->data['company_id'] = $this->input->post('company_id', true);
        $this->data['phone'] = $this->input->post('phone', true);
        $this->data['password'] = $this->input->post('password', true);
    }

}