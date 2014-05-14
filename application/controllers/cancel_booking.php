<?php

if(!defined('BASEPATH'))
    exit('No direct script access allowed');

class Cancel_booking extends My_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('bookings_model');
        $this->load->helper('send_message');
    }

    public function index($hash = null) {
        if(empty($hash)){
            redirect(base_url());
        }
        $dataArr = json_decode(base64_decode($hash), true);
        if(empty($dataArr) || !isset($dataArr['booking_id']) || !isset($dataArr['user_id'])){
            redirect(base_url());
        }
        $bookingData = $this->bookings_model->get($dataArr['booking_id']);
        if(empty($bookingData) || $bookingData->user_id != $dataArr['user_id']){
            redirect(base_url());
        }
        else {
            $nowDate = time();
            $checkDate = strtotime('-2 hours', strtotime($bookingData->from));
            if($nowDate < $checkDate){
                redirect(base_url());
            }else{
                $this->session->set_userdata(array('cancel_booking' => $dataArr));
                redirect(base_url() . 'merchant/dashboard/index');
            }
        }
       
    }

}