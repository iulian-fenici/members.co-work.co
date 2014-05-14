<?php

if(!defined('BASEPATH'))
    exit('No direct script access allowed');

class Statistics extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->checkAdminLoggedIn();
        $this->load->model(array('stats_model'));
    }
    
    public function statistic()
    {
        $dateInterval = $this->getInterval();
        
        $this->data = array();
        $this->data['dateFrom'] = $dateInterval['dateFrom'];
        $this->data['dateTo'] = $dateInterval['dateTo'];
        $this->data['resultType'] = 'time';
        $this->viewData['header'] = $this->load->view('admin/main/header', $this->data, true);
        $this->viewData['menu'] = $this->load->view('admin/main/menu', $this->data, true);
        $this->viewData['sidebar'] = $this->load->view('admin/main/sidebar', $this->data, true);
        $this->viewData['message'] = $this->load->view('admin/main/message', $this->data, true);
        $this->viewData['content'] = $this->load->view('admin/statistics/bookingBy', $this->data, true);
        $this->viewData['footer'] = $this->load->view('admin/main/footer', $this->data, true);
        $this->load->view('admin/main/content', $this->viewData);
    }
    
    
    public function bookingByUsers()
    {
        $inputData['dateFrom'] = $this->input->post('date_from');
        $inputData['dateTo'] = $this->input->post('date_to');
        $inputData['resultType'] = $this->input->post('resultType')?$this->input->post('resultType'):'time';
        
        $statisticInfo = $this->stats_model->getBookingBy('users', 'user_id', $inputData);
    //var_dump($statisticInfo);
        if($statisticInfo){
            $data = array(
                'success' => 1,
                'title' => 'Bookings By Users',
                'resultType' => $inputData['resultType'],
                'series' => $statisticInfo['series'],
                'categories' => $statisticInfo['categories']
            );
        }
        else {
            $data = array(
                'error' => 1
            );
        }
        $this->load->view('merchant/echodata', array('data' => json_encode($data)));
        
    }
    
    public function bookingByCompanies()
    {
        $inputData['dateFrom'] = $this->input->post('date_from');
        $inputData['dateTo'] = $this->input->post('date_to');
        $inputData['resultType'] = $this->input->post('resultType')?$this->input->post('resultType'):'time';
        $statisticInfo = $this->stats_model->getBookingBy('companies', 'company_id', $inputData);
    
        if($statisticInfo){
            $data = array(
                'success' => 1,
                'title' => 'Bookings By Companies',
                'resultType' => $inputData['resultType'],
                'series' => $statisticInfo['series'],
                'categories' => $statisticInfo['categories']
            );
        }
        else {
            $data = array(
                'error' => 1
            );
        }
        $this->load->view('merchant/echodata', array('data' => json_encode($data)));
        
    }
    
    public function bookingByLocations()
    {
        $inputData['dateFrom'] = $this->input->post('date_from');
        $inputData['dateTo'] = $this->input->post('date_to');
        $inputData['resultType'] = $this->input->post('resultType')?$this->input->post('resultType'):'time';
        $statisticInfo = $this->stats_model->getBookingBy('locations', 'location_id', $inputData);
    
        if($statisticInfo){
            $data = array(
                'success' => 1,
                'title' => 'Bookings By Locations',
                'resultType' => $inputData['resultType'],
                'series' => $statisticInfo['series'],
                'categories' => $statisticInfo['categories']
            );
        }
        else {
            $data = array(
                'error' => 1
            );
        }
        $this->load->view('merchant/echodata', array('data' => json_encode($data)));
        
    }
    
    private function getInterval() {
        $dateInterval = $this->input->get('date_interval') ? $this->input->get('date_interval') : '';
        
        if ($dateInterval != '' && $dateInterval != '|') {
            $dateInterval = explode('|', $dateInterval);
            $dateFrom = isset($dateInterval[0]) && !empty($dateInterval[0]) ? $dateInterval[0] . ' 00:00:00' : false;
            $dateTo = isset($dateInterval[1]) && !empty($dateInterval[1]) ? $dateInterval[1] . ' 23:59:59' : false;
        }
        else {
            return false;
        }
        return array('dateFrom' => $dateFrom, 'dateTo' => $dateTo);
    }   
}
