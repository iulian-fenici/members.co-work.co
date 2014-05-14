<?php

if(!defined('BASEPATH'))
    exit('No direct script access allowed');

class Registration extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->checkAdminLoggedIn();
        $this->load->model(array('users_model', 'companies_model'));
        $this->load->library(array('form_validation', 'ion_auth'));
    }
    
    public function send_invite()
    {
        $this->data = array();
        $companiesList = $this->companies_model->getCompaniesListDD();
        $this->data['companiesList'] = form_dropdown('company_id', $companiesList, set_value('company_id', isset($getArr['company_id'])?$getArr['company_id']:0), 'id="company_id"');
        $this->data['base_url'] = base_url();
        $this->_setInviteValidationRules();

        if($this->input->post('save')){
            
            if($this->form_validation->run() == true){
                $this->_getDataFromInviteForm();   

                if($link = $this->_sendInvite($this->data))
                    $this->messages->add("Invite successfully sent. Registration link: <b>".$link.'</b>', "success");
                redirect('/admin/registration/send_invite');
            }
            else {
                //validation errors
               // var_dump(validation_errors());
            }
        }
        $this->viewData['header'] = $this->load->view('admin/main/header', $this->data, true);
        $this->viewData['menu'] = $this->load->view('admin/main/menu', $this->data, true);
        $this->viewData['sidebar'] = $this->load->view('admin/main/sidebar', $this->data, true);
        $this->viewData['message'] = $this->load->view('admin/main/message', $this->data, true);
        $this->viewData['content'] = $this->load->view('admin/registration/send_invite', $this->data, true);
        $this->viewData['footer'] = $this->load->view('admin/main/footer', $this->data, true);
        $this->load->view('admin/main/content', $this->viewData);
    }
    
    private function _setInviteValidationRules()
    {
        $this->form_validation->set_rules('company_id', 'Company', 'required|xss_clean|trim');
        $this->form_validation->set_rules('email', 'User Email', 'valid_email|required|xss_clean|trim');
    }
    
    private function _getDataFromInviteForm() {
        $this->data['company_id'] = $this->input->post('company_id', true);
        $this->data['email'] = $this->input->post('email', true);
    }
    
    private function _sendInvite($data)
    {
        $str = '&#12(&ghghghjgg';
        $encoded = base64_encode($str.$data['company_id']);
        $formData['url'] = base_url().'merchant/signup?id='.$encoded;
        $this->load->helper('send_message');
        $msgToSend = $this->load->view('emails/registration_invite', $formData, true);
        //var_dump($msgToSend)        ;die;
        $res = _sendEmail($this->config->item('admin_notication_email'), $data['email'], $msgToSend, 'Invite to registration on '.site_url()) ;
        return $formData['url'];
    }
    
    
}