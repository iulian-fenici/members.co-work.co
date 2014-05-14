<?php

if(!defined('BASEPATH'))
    exit('No direct script access allowed');

class Comments extends MY_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->checkLoggedIn();
        $this->load->model(array('comments_model'));
        $this->load->library(array('form_validation', 'ion_auth'));
    }
    
    public function add_comment($Announce_id)
    {
        if ($this->input->post())
        {
            $this->form_validation->set_rules('comment_text', 'Comment text', 'required');
            if ($this->form_validation->run() != FALSE)
            {
                $form_data = $this->input->post();
                $data['user_id'] = $this->session->userdata('user_id');
                $data['company_id'] = $this->session->userdata('company_id');
                $data['announcement_id'] = $Announce_id;
                $data['comment_text'] = $form_data['comment_text'];
                $data['date'] = date('Y-m-d H:i:s');
                $this->comments_model->addComent($data);
            }
        }
        if (validation_errors('<span class="error">','</span>'))
                $this->session->set_flashdata('error', validation_errors('<span class="error">','</span>'));
     
        redirect('/admin/announcement/view/'.$Announce_id);
    }
}