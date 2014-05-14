<?php

if(!defined('BASEPATH'))
    exit('No direct script access allowed');

class Booking extends MY_Controller {

    public function __construct() {
        //var_dump($this->session->all_userdata());
        parent::__construct();
        $this->checkLoggedIn();
        $this->load->model(array('users_model', 'bookings_model', 'rooms_model', 'notifications_model','locations_model'));
        $this->load->helper(array('dates_helper', 'send_message'));
        $this->load->library(array('form_validation', 'ion_auth'));
    }

    public function index() {
        $this->data = array();
        $usersDDArr = $this->users_model->getUserListDD($this->session->userdata('user_id'), $this->session->userdata('company_id'), 'Myself');
        $this->data['usersDD'] = form_dropdown('for_user_id', $usersDDArr, set_value('for_user_id', ''));
        //view merchant
        $this->viewData['header'] = $this->load->view('merchant/main/header', $this->data, true);
        $this->viewData['menu'] = $this->load->view('merchant/main/menu', $this->data, true);
        $this->viewData['sidebar'] = $this->load->view('merchant/main/sidebar', $this->data, true);
        $this->viewData['message'] = $this->load->view('merchant/main/message', $this->data, true);
        $this->viewData['content'] = $this->load->view('merchant/booking/wizard', $this->data, true);
        $this->viewData['footer'] = $this->load->view('merchant/main/footer', $this->data, true);
        $this->load->view('merchant/main/content', $this->viewData);
    }

    public function link_expired() {
        $this->load->view('merchant/booking/link_expired');
    }

//    public function delete_booking($bookingId) {
//        $this->data['bookingData'] = $this->bookings_model->get($bookingId);
//        $this->load->view('merchant/booking/delete_booking', $this->data);
//    }
    
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
    
    public function cancel_booking($bookingId) {
        $bookingData = $this->notifications_model->getBookingDataByBookingId($bookingId);
        if(isset($bookingData) && !empty($bookingData['user_id'])){
            $notificationData = array(
                'user_id' => $bookingData['user_id'],
                'booking_id' => $bookingData['booking_id'],
                'type' => 3,
                'created_at' => date('Y-m-d H:i:s')
            );
            notify($notificationData, $bookingData);
        }
        $res = $this->bookings_model->cancelBooking($bookingId);
        if($res){
            $data = array(
                'success' => 1,
            );
        }
        else {
            $data = array(
                'error' => 1,
            );
        }
        $this->load->view('merchant/echodata', array('data' => json_encode($data)));
    }

    public function add_booking() {       
        if($this->input->post('save')){

            $this->data['date'] = $this->input->post('date', true);
            $this->data['time'] = $this->input->post('time', true);
            $time = date('H:i:s', strtotime($this->data['time']));
            $this->data['duration'] = $this->input->post('duration', true);
            $this->data['room_id'] = $this->input->post('room_id', true);
            $this->data['description'] = $this->input->post('comment', true);
            $this->data['for_user_id'] = $this->input->post('for_user_id', true);
            $location = $this->rooms_model->getLocationByRoomId( $this->data['room_id']);
            $this->data['location_id'] = $location['id'];            
            $this->data['duration'] = preg_replace('/[^0-9\:]/', '', $this->data['duration']);
            $insertBookingsData = array(
                'location_id' => $this->data['location_id'],
                'company_id' => $this->session->userdata('company_id'),
                'user_id' => $this->session->userdata('user_id'),
                'room_id' => $this->data['room_id'],
                'from' => date('Y-m-d H:i:s', strtotime($this->data['date'] . ' ' . $time)),
                'to' => _getDateTo(date('Y-m-d H:i:s', strtotime($this->data['date'] . ' ' . $time)), $this->data['duration']),
                'duration' => $this->data['duration'],
                'description' => $this->data['description']
            );
            if(!empty($this->data['for_user_id'])){
                $insertBookingsData['for_user_id'] = $this->data['for_user_id'];
            }
            
            ////////////////////////////////////////////////////        
            $options['location_id'] = $this->data['location_id'];
            $options['duration'] = $this->data['duration'];
            $options['dateFrom'] = $insertBookingsData['from'];
            $options['dateTo'] = $insertBookingsData['to'];
            $options['room_id'] = $this->data['room_id'];
            //     $options['company_id'] = $this->session->userdata('company_id');

            $userLocationsIds = $this->locations_model->getLocationsVariableByCompanyId($this->session->userdata('company_id'),'id');
            //$roomBooking = $this->bookings_model->getRoomBookings($this->data['room_id'], $this->data['date']);
            $roomBookings = $this->bookings_model->getRoomBookingsByDate($userLocationsIds, $this->data['date'],null,false);
            $companyRoomBookings = $this->bookings_model->getRoomBookingsByDate($userLocationsIds, $this->data['date'], $this->session->userdata('company_id'),false);

            $res = $this->_checkBookingsDashboard($roomBookings, $options,$this->data['room_id'], $companyRoomBookings);


            if(!empty($res['timeIntersection']))
            {
                $res['bookingsErrorArr'][]='Time intersection with other bookings';
            }

            if(!empty($res['bookingsErrorArr'])){
                $this->messages->add("Error on creating booking", "error");
                redirect('/merchant/dashboard/index');
            }

            ////////////////////////////////////////////////////         

            
            $bookingId = $this->bookings_model->insert($insertBookingsData);
            if($bookingId){
                // Booking created notification
                $this->load->model('notifications_model');
                $bookingData = $this->notifications_model->getBookingDataByBookingId($bookingId);
                log_message('error', "booking creation. bookingId=" . $bookingId . " data=" . print_r($bookingData, true));
                if(isset($bookingData) && !empty($bookingData['user_id'])){
                    $notificationData = array(
                        'user_id' => $bookingData['user_id'],
                        'booking_id' => $bookingData['booking_id'],
                        'type' => 2,
                        'created_at' => date('Y-m-d H:i:s')
                    );
                    notify($notificationData, $bookingData);
                }
                $this->messages->add("Booking created successfully", "success");
            }
            else
                $this->messages->add("Error on creating booking", "error");
            redirect('/merchant/dashboard/index');
        }
    }

    public function add_booking_popup($bookingId = false) {
        $this->load->model('locations_model');
        settype($bookingId,'integer');
        $this->bookingId = $bookingId;
        $this->data['bookingData'] =false;
        if(!empty($this->bookingId)){
            $this->data['bookingData'] = $this->bookings_model->get($this->bookingId);
            if(empty($this->data['bookingData'])){
                $data = array(
                    'error' => 1,
                    'text' => 'This booking is not valid'
                );
                $this->load->view('merchant/echodata', array('data' => json_encode($data)));
                return;
            }
        }
        
        $this->data['jsStartHours']= date('g:i a',strtotime($this->input->post('jsStartHours')));
        $this->data['jsEndHours']= date('g:i a',strtotime($this->input->post('jsEndHours')));
        $this->data['jsDate']= date('d/m/Y',strtotime($this->input->post('jsDate')));
        $this->data['jsDuration']= $this->input->post('jsDuration');
        $this->data['jsRoomId']= $this->input->post('jsRoomId');

        $usersDDArr = $this->users_model->getUserListDD($this->session->userdata('user_id'), $this->session->userdata('company_id'), 'Myself');
        $this->data['usersDD'] = form_dropdown('for_user_id', $usersDDArr, set_value('for_user_id', ''));
        $this->_setValidationRules();
        if($this->input->post('save')){            
            if($this->form_validation->run() == true){               
                $this->_getDataFromForm();
                $result = $this->_saveData();
                if(is_array($result)){
                    $this->data = array(
                        'error' => 1,
                        'text' => implode('. ', $result['bookingsErrorArr'])
                    );
                    $this->load->view('merchant/echodata', array('data' => json_encode($this->data)));
                    return false;
                }
                else {
                    $this->data = array(
                        'success' => 1,
                    );
                }
                $this->load->view('merchant/echodata', array('data' => json_encode($this->data)));
                return;
            }
            else {
                $this->data = array(
                    'error' => 1,
                    'text' => validation_errors()
                );
                $this->load->view('merchant/echodata', array('data' => json_encode($this->data)));
                return false;
            }
        }

        $this->load->view('merchant/booking/add_booking_popup', $this->data);
    }

    public function get_booking_rooms() {
        $options = array(
            'date' => $this->input->post('date', true),
            'time' => $this->input->post('time', true),
            'duration' => $this->input->post('duration', true),
        );
        $options['dateFrom'] = date('Y-m-d H:i:s', strtotime($options['date'] . ' ' . $options['time']));
        $options['dateTo'] = _getDateTo(date('Y-m-d H:i:s', strtotime($options['date'] . ' ' . $options['time'])), $options['duration']);
        
        $userLocationsIds = $this->locations_model->getLocationsVariableByCompanyId($this->session->userdata('company_id'),'id');
        $roomBookings = $this->bookings_model->getRoomBookingsByDate($userLocationsIds, $options['date']);
        $companyRoomBookings = $this->bookings_model->getRoomBookingsByDate($userLocationsIds, $options['date'], $this->session->userdata('company_id'));
        $resultArr = $this->_checkBookingsWizard($roomBookings, $options,false,$companyRoomBookings);
        $data = array(
            'success' => 1,
            'html' => $this->load->view('merchant/rooms/rooms_list', array('rooms' => $resultArr['bookingsArr'], 'bookingErrors' => $resultArr['bookingsErrorArr']), true)
        );
        $this->load->view('merchant/echodata', array('data' => json_encode($data)));
    }

    private function _checkBookingsWizard($dataArr, $options ,$roomId = false, $companyRoomBookings = false) {
        if(empty($dataArr)){
            return array();
        }
        $bookingsErrorArr = array();
        $timeIntersection = false;
        $businessTime = false;
        
        if(checkBookingBusinessTime($options)){
            $businessTime = true;
        }
        
        if(empty($bookingsErrorArr)){
            if(!checkBookingPassedTime($options)){
                $bookingsErrorArr[] = 'Booking at this time is not possible, because the time already passed';
            }
        }
        if(empty($bookingsErrorArr)){
            if(!checkBookingLength($options,$businessTime)){
                $bookingsErrorArr[] = 'Booking length is more than 2 hours';
            }
        }
        
        if(empty($bookingsErrorArr) && $businessTime){           
            if(!checkInlineRoomsBooking($companyRoomBookings, $options)){
                $bookingsErrorArr[] = 'Inline booking count must be less than 2';
            }
        }
        if(empty($bookingsErrorArr)){
            if(!checkDuration($options['dateFrom'], $options['duration'])){
                $bookingsErrorArr[] = 'Booking duration is outside of the day';
            }
        }
        if(empty($bookingsErrorArr)){
            foreach ($dataArr as $key => $room){
                $options['location_id'] = $room['location_id'];
                $options['room_id'] = $room['room_id'];
                $dataArr[$key]['bookingForFurtherTime'] = checkBookingForFurtherTime($companyRoomBookings,$options);
                if(!empty($room['bookings'])){
                    foreach ($room['bookings'] as $k => $v){
                        if(!checkBookingInterval($v['from'], $v['duration'], $v['to'], $options['dateFrom'], $options['dateTo'])){
                            $timeIntersection = true;
                            $dataArr[$key]['timeIntersect'] = true;
                            $dataArr[$key]['nexAvailableBooking'] = checkNextAvailableBooking($v['from'], $v['duration'], $v['to'], $options['dateFrom'], $options['duration'], $options['dateTo']);
                            break;
                        }
                    }
                }
            }
        }
        
        return array('bookingsArr' => $dataArr, 'bookingsErrorArr' => $bookingsErrorArr, 'timeIntersection' => $timeIntersection);
    }
    
    private function _checkBookingsDashboard($dataArr, $options ,$roomId = false, $companyRoomBookings = false) {
        if(empty($dataArr)){
            return array();
        }
        $bookingsErrorArr = array();
        $timeIntersection = false;
        $businessTime = false;
        
        if(checkBookingBusinessTime($options)){
            $businessTime = true;
        }
        
        if(empty($bookingsErrorArr)){
            if(!checkBookingPassedTime($options)){
                $bookingsErrorArr[] = 'Booking at this time is not possible, because the time already passed';
            }
        }
        if(empty($bookingsErrorArr)){
            if(!checkBookingLength($options,$businessTime)){
                $bookingsErrorArr[] = 'Booking length is more than 2 hours';
            }
        }
        if(empty($bookingsErrorArr)){
            if(!checkDuration($options['dateFrom'], $options['duration'])){
                $bookingsErrorArr[] = 'Duration too long';
            }
        }
        if(empty($bookingsErrorArr)){
            foreach ($dataArr as $key => $room){
                if ($roomId != false){
                    if ($room['room_id'] != $roomId)
                    {
                        continue;
                    }
                }
                if(!empty($room['bookings'])){
                    foreach ($room['bookings'] as $k => $v){         
                        if(!checkBookingInterval($v['from'], $v['duration'], $v['to'], $options['dateFrom'], $options['dateTo'])){
                            $timeIntersection = true;
                            $dataArr[$key]['timeIntersect'] = true;
                            $dataArr[$key]['nexAvailableBooking'] = checkNextAvailableBooking($v['from'], $v['duration'], $v['to'], $options['dateFrom'], $options['duration'], $options['dateTo']);
                            break;
                        }
                    }
                }
            }
        }
        
        if(empty($bookingsErrorArr) && $businessTime){           
            if(!checkInlineRoomsBooking($companyRoomBookings, $options)){
                $bookingsErrorArr[] = 'Inline booking count must be less than 2';
            }
        }
        
       
        if(empty($bookingsErrorArr) && $businessTime){
            if(!checkBookingForFurtherTime($companyRoomBookings,$options)){
                $bookingsErrorArr[] = 'Тime between booking must be over 2 hours';
            }
        }

        return array('bookingsArr' => $dataArr, 'bookingsErrorArr' => $bookingsErrorArr, 'timeIntersection' => $timeIntersection);
    }


    private function _setValidationRules() {
        $this->form_validation->set_rules('date', 'Date', 'required|xss_clean|trim');
        $this->form_validation->set_rules('time', 'Time', 'required|xss_clean|trim');
        $this->form_validation->set_rules('duration', 'Duration', 'required|xss_clean|trim|callback__checkDuration');
        $this->form_validation->set_rules('comment', 'Comment', 'xss_clean|trim');
       
        if (!empty($this->data['bookingData'])) {
            $this->form_validation->set_rules('room_id', 'Room id', 'xss_clean|trim');
        }else{
            $this->form_validation->set_rules('room_id', 'Room id', 'required|xss_clean|trim');
        }
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
    
        public function checkUniqueEmail($email)
    {
        $this->load->model('ion_auth_model');
        $found = $this->ion_auth_model->email_check($email, true);
        if ($found){
            $this->form_validation->set_message('checkUniqueEmail', 'Not unique email.');
            return false;            
        }
        else {
            return true;
        }
    }

    private function _getDataFromForm() {
        if (!empty($this->data['bookingData'])) {
            $this->data['date'] = $this->input->post('date', true);
            $this->data['time'] = $this->input->post('time', true);
            $this->data['duration'] = $this->input->post('duration', true);
            $this->data['comment'] = $this->input->post('comment', true);
            $this->data['user_id'] = $this->session->userdata('user_id');
            $this->data['company_id'] = $this->session->userdata('company_id');
            $this->data['room_id'] = $this->data['bookingData']->id;
            $this->data['for_user_id'] = $this->data['bookingData']->for_user_id;
            $this->data['location_id'] = $this->data['bookingData']->location_id;
        }else{
            $this->data['date'] = $this->input->post('date', true);
            $this->data['time'] = $this->input->post('time', true);
            $this->data['duration'] = $this->input->post('duration', true);
            $this->data['comment'] = $this->input->post('comment', true);
            $this->data['user_id'] = $this->session->userdata('user_id');
            $this->data['company_id'] = $this->session->userdata('company_id');
            $this->data['room_id'] = $this->input->post('room_id', true);
            $this->data['for_user_id'] = $this->input->post('for_user_id', true);
            $location = $this->rooms_model->getLocationByRoomId( $this->data['room_id']);
            $this->data['location_id'] = $location['id'];
        }
    }

    private function _saveData() {
        $dateArr = explode('/',$this->data['date']);
        $time = date('H:i:s', strtotime($this->data['time']));
        $this->data['duration'] = preg_replace('/[^0-9\:]/', '', $this->data['duration']);
        if (!empty($this->data['bookingData'])) {
            $insertBookingsData = array(
                'user_id' => $this->data['user_id'],
                'from' => $dateArr[2] . '-' . $dateArr[1] . '-' . $dateArr[0] . ' ' . $time,
                'to' => _getDateTo($dateArr[2] . '-' . $dateArr[1] . '-' . $dateArr[0] . $time, $this->data['duration']),
                'duration' => $this->data['duration'],
                'description' => $this->data['comment']
            );
            $this->data['room_id'] = $this->data['bookingData']->room_id;
        } else {
            $insertBookingsData = array(
                'location_id' => $this->data['location_id'],
                'company_id' => $this->data['company_id'],
                'user_id' => $this->data['user_id'],
                'room_id' => $this->data['room_id'],
                'from' => $dateArr[2] . '-' . $dateArr[1] . '-' . $dateArr[0] . ' ' . $time,
                'to' => _getDateTo($dateArr[2] . '-' . $dateArr[1] . '-' . $dateArr[0] . $time, $this->data['duration']),
                'duration' => $this->data['duration'],
                'description' => $this->data['comment']
            );
        }

        if(!empty($this->data['for_user_id'])){
            $insertBookingsData['for_user_id'] = $this->data['for_user_id'];
        }
        $options['location_id'] = $this->data['location_id'];
        $options['duration'] = $this->data['duration'];
        $options['dateFrom'] = $insertBookingsData['from'];
        $options['dateTo'] = $insertBookingsData['to'];
        $options['room_id'] = $this->data['room_id'];
   //     $options['company_id'] = $this->session->userdata('company_id');
        
        $userLocationsIds = $this->locations_model->getLocationsVariableByCompanyId($this->session->userdata('company_id'),'id');
        //$roomBooking = $this->bookings_model->getRoomBookings($this->data['room_id'], $this->data['date']);
        $roomBookings = $this->bookings_model->getRoomBookingsByDate($userLocationsIds, $this->data['date'],null,$this->bookingId);
        $companyRoomBookings = $this->bookings_model->getRoomBookingsByDate($userLocationsIds, $this->data['date'], $this->session->userdata('company_id'),$this->bookingId);
        
        $res = $this->_checkBookingsDashboard($roomBookings, $options,$this->data['room_id'], $companyRoomBookings);

        if(!empty($res['bookingsErrorArr'])){
            return $res;
        }
        
        if(!empty($res['timeIntersection']))
        {
            $res['bookingsErrorArr'][]='Time intersection with other bookings';
            return $res;
        }
        if (!empty($this->data['bookingData'])) {
            $this->bookings_model->update($this->data['bookingData']->id,$insertBookingsData);
            $bookingId = $this->data['bookingData']->id;
        }else{
            $bookingId = $this->bookings_model->insert($insertBookingsData);
            $bookingData = $this->notifications_model->getBookingDataByBookingId($bookingId);
            if(isset($bookingData) && !empty($bookingData['user_id'])){
                $notificationData = array(
                    'user_id' => $bookingData['user_id'],
                    'booking_id' => $bookingData['booking_id'],
                    'type' => 2,
                    'created_at' => date('Y-m-d H:i:s')
                );
                notify($notificationData, $bookingData);
            }
        }
        return $bookingId;
    }
    
    public function edit_booking($bookingId = null) {
        if(empty($bookingId)){
            return false;
        }

        $this->data['bookingData'] = $this->bookings_model->getUserBooking($bookingId, $this->session->userdata('user_id'));
        
        $this->load->view('merchant/booking/edit_booking', array('bookingData' => $this->data['bookingData']));
    }
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */