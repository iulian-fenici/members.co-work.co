<?php

if(!defined('BASEPATH'))
    exit('No direct script access allowed');

//Admin panel 
class Booking extends My_Controller {

    public function __construct() {
        parent::__construct();
        $this->checkAdminLoggedIn();
        $this->load->model(array('bookings_model', 'locations_model','rooms_model', 'notifications_model', 'companies_model', 'users_model'));
        $this->load->library(array('form_validation', 'ion_auth'));
        $this->load->helper(array('dates_helper','send_message_helper'));
    }

    public function edit_booking($bookingId = null) {
        if(empty($bookingId)){
            return false;
        }

        $this->data['bookingData'] = $this->bookings_model->get($bookingId);
        $this->load->view('admin/booking/edit_booking', array('bookingData' => $this->data['bookingData']));
    }

    public function add_booking() {
        $locationsList = $this->locations_model->getLocationsListDD();
        
        $this->data = array(
            'jsStartHours' => date('g:i a',strtotime($this->input->post('jsStartHours'))),
            'jsEndHours' => date('g:i a',strtotime($this->input->post('jsEndHours'))),
            'jsDate' => date('d/m/Y',strtotime($this->input->post('jsDate'))),
            'jsDuration' => $this->input->post('jsDuration'),
            'jsRoomId' => $this->input->post('jsRoomId'),
        );

        $this->_setValidationRules();
        if($this->input->post('save')){

            if($this->form_validation->run() == true){
                $this->_getDataFromForm();

                if($this->_saveData()){
                    $data = array(
                        'success' => 1,
                    );
                }
                else {
                    if(isset($this->intersectedInterval) && $this->intersectedInterval){
                        $data = array(
                            'error' => 1,
                            'text' => 'Cannot edit this booking time intersection'
                        );
                        $this->load->view('merchant/echodata', array('data' => json_encode($data)));
                        return false;
                    }
                    
                    if(isset($this->durationError) && !empty($this->durationError)){
                        $data = array(
                            'error' => 1,
                            'text' => $this->durationError
                        );
                        $this->load->view('merchant/echodata', array('data' => json_encode($data)));
                        return false;
                    }
                }
                $this->load->view('merchant/echodata', array('data' => json_encode($data)));
                return;
            }
            else {
                $data = array(
                    'error' => 1,
                    'text' => validation_errors()
                );
                $this->load->view('merchant/echodata', array('data' => json_encode($data)));
                return false;
            }
        }

        $this->data['locationsList'] = form_dropdown('location_id', $locationsList, set_value('location_id', 0), 'id="location_id"');
        $this->load->view('admin/booking/add_booking/add_booking', $this->data);
    }

    private function _setValidationRules() {
        $this->form_validation->set_rules('date', 'Date', 'required|xss_clean|trim');
        $this->form_validation->set_rules('time', 'Time', 'required|xss_clean|trim');
        $this->form_validation->set_rules('duration', 'Duration', 'required|xss_clean|trim|callback__checkDuration');
        $this->form_validation->set_rules('comment', 'Comment', 'xss_clean|trim');
        $this->form_validation->set_rules('user_id', 'User id', 'required|xss_clean|trim');
        $this->form_validation->set_rules('location_id', 'Location id', 'required|xss_clean|trim');
        $this->form_validation->set_rules('company_id', 'Company id', 'required|xss_clean|trim');
        $this->form_validation->set_rules('room_id', 'Room id', 'required|xss_clean|trim');
        $this->form_validation->set_rules('for_user_id', 'For User id', 'xss_clean|trim');
    }
    
    public function _checkDuration($duration)
    {
        $this->load->helper('dates_helper');
        $res = checkDurationValue($duration);

        if (checkDurationValue($duration) == false){
            $this->form_validation->set_message('_checkDuration', 'Not valid duration value.');
            return false;            
        }
        else {
            return true;
        }        
    }    

    private function _getDataFromForm() {
        $this->data['date'] = $this->input->post('date', true);
        $this->data['time'] = $this->input->post('time', true);
        $this->data['duration'] = $this->input->post('duration', true);
        $this->data['comment'] = $this->input->post('comment', true);
        $this->data['user_id'] = $this->input->post('user_id', true);
        $this->data['location_id'] = $this->input->post('location_id', true);
        $this->data['company_id'] = $this->input->post('company_id', true);
        $this->data['room_id'] = $this->input->post('room_id', true);
        $this->data['for_user_id'] = $this->input->post('for_user_id', true);
    }

    private function _saveData() {
        $dateArr = explode('/',$this->data['date']);
        $time = date('H:i:s', strtotime($this->data['time']));
        $this->data['duration'] = preg_replace('/[^0-9\:]/', '', $this->data['duration']);
        $this->durationError = false;
        if (!checkDuration($dateArr[2].'-'.$dateArr[1].'-'.$dateArr[0] . ' ' . $time, $this->data['duration']))
        {
            $this->durationError = 'Booking duration is outside of the day';
            return false;
        }
        $insertBookingsData = array(
            'location_id' => $this->data['location_id'],
            'company_id' => $this->data['company_id'],
            'user_id' => $this->data['user_id'],
            'room_id' => $this->data['room_id'],
            'from' => $dateArr[2].'-'.$dateArr[1].'-'.$dateArr[0] . ' ' . $time,
            'to' => _getDateTo($dateArr[2].'-'.$dateArr[1].'-'.$dateArr[0] . $time, $this->data['duration']),
            'duration' => $this->data['duration'],
            'description' => $this->data['comment']
        );
        if(!empty($this->data['for_user_id'])){
            $insertBookingsData['for_user_id'] = $this->data['for_user_id'];
        }

        $this->intersectedInterval = false;
        $roomBookings = $this->bookings_model->getRoomBookings($this->data['room_id'], $this->data['date']);

        foreach ($roomBookings as $v){
            if(!checkBookingInterval($v['from'], $v['duration'], $v['to'], $insertBookingsData['from'], $insertBookingsData['to'])){
                $this->intersectedInterval = true;
                break;
            }
        }

        if($this->intersectedInterval){
            return false;
        }
        
        $bookingId = $this->bookings_model->insert($insertBookingsData);
        
        $bookingData = $this->notifications_model->getBookingDataByBookingId($bookingId);
        if(isset($bookingData) && !empty($bookingData['user_id'])){
            $notificationData = array(
                'user_id' => $bookingData['user_id'],
                'booking_id' => $bookingData['booking_id'],
                'type' => 4,
                'created_at' => date('Y-m-d H:i:s')
            );
            notify($notificationData, $bookingData);
        }
        
        return $bookingId;
    }

    public function booking_list() {
        $this->load->library('pagination');
        $page = (int) $this->input->get('page');
        $this->load->config('pagination', true);
        $getArr = $this->input->get();
        $configPagination = $this->config->item('pagination_default', 'pagination');
        
        //prepare get
        if(isset($getArr['from'])){
            $dateArr = explode('/',$getArr['from']);
            $getArr['from'] = $dateArr[2].'-'.$dateArr[1].'-'.$dateArr[0].' 00:00:00';
            
        }
        if(isset($getArr['to'])){
            $dateArr = explode('/',$getArr['to']);
            $getArr['to'] = $dateArr[2].'-'.$dateArr[1].'-'.$dateArr[0].' 23:59:59';
        }
        $locationsList = $this->locations_model->getLocationsListDD();
        $this->data['locationsList'] = form_dropdown('location_id', $locationsList, set_value('location_id', isset($getArr['location_id'])?$getArr['location_id']:0), 'id="location_id"');
        
        if(isset($getArr['location_id'])){
            $companiesList = $this->companies_model->getCompaniesListDD($getArr['location_id']);
            $this->data['companiesList'] = form_dropdown('company_id', $companiesList, set_value('company_id', isset($getArr['company_id'])?$getArr['company_id']:0), 'id="company_id"');
        }
       
        if(isset($getArr['company_id'])){
            $usersList = $this->users_model->getUserListDD(null, $getArr['company_id']);
            $this->data['usersList'] = form_dropdown('user_id', $usersList, set_value('user_id', isset($getArr['user_id'])?$getArr['user_id']:0), 'id="user_id"');
        }
        if(isset($getArr['user_id'])&&isset($getArr['company_id'])){
            $forUsersList = $this->users_model->getUserListDD($getArr['user_id'], $getArr['company_id']);
            $this->data['forUsersList'] = form_dropdown('for_user_id', $forUsersList, set_value('for_user_id', isset($getArr['for_user_id'])?$getArr['for_user_id']:0), 'id="for_user_id"');
        }
        if(isset($getArr['location_id'])){
            $roomsList = $this->rooms_model->getRoomsListDD($getArr['location_id']);
            $this->data['roomsList'] = form_dropdown('room_id', $roomsList, set_value('room_id', isset($getArr['room_id'])?$getArr['room_id']:0), 'id="room_id"');
        }
        
        $this->data['bookingsList'] = $this->bookings_model->getBookingsList($getArr, array($this->input->get('order', false) => $this->input->get('direction', false)), $configPagination['per_page'], $page);

        $this->load->config('pagination', true);


        $configPagination['base_url'] = $this->utility->build_filter_href('/admin/booking/booking_list', array('page' => null));
        $configPagination['total_rows'] = $this->bookings_model->getCountBookings($getArr);
        //var_dump($configPagination['total_rows']);die();
        $this->pagination->initialize($configPagination);
        $this->data['pagination'] = $this->pagination->create_links();

        //var_dump( $this->data['bookingsList']);        
        $this->viewData['header'] = $this->load->view('admin/main/header', $this->data, true);
        $this->viewData['menu'] = $this->load->view('admin/main/menu', $this->data, true);
        $this->viewData['sidebar'] = $this->load->view('admin/main/sidebar', $this->data, true);
        $this->viewData['message'] = $this->load->view('admin/main/message', $this->data, true);
        $this->viewData['content'] = $this->load->view('admin/booking/booking_list', $this->data, true);
        $this->viewData['footer'] = $this->load->view('admin/main/footer', $this->data, true);
        $this->load->view('admin/main/content', $this->viewData);
    }

    public function delete_booking($bookingId) {
        $res = $this->bookings_model->cancelBooking($bookingId);
        if ($res) {
            $bookingData = $this->notifications_model->getBookingDataByBookingId($bookingId);
            $this->load->helper('dates');
            if (checkBookingForNotificationOnCancel($bookingData['from'])) {
                if (isset($bookingData) && !empty($bookingData['user_id'])) {
                    $notificationData = array(
                        'user_id' => $bookingData['user_id'],
                        'booking_id' => $bookingData['booking_id'],
                        'type' => 6,
                        'created_at' => date('Y-m-d H:i:s')
                    );
                    notify($notificationData, $bookingData);
                }
            }
            $this->messages->add("Booking canceled", "success");
            
        } else {
            $this->messages->add("Booking not canceled", "error");
        }
        redirect('/admin/booking/booking_list');
    }

}
