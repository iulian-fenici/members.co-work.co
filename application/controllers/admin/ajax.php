<?php

class Ajax extends My_Controller {

    public function __construct() {
        parent::__construct();
        $this->checkAdminLoggedIn();
        $this->load->model(array('bookings_model', 'locations_model', 'companies_model','notifications_model', 'rooms_model', 'users_model'));
        $this->load->library(array('form_validation', 'ion_auth'));
        $this->load->helper(array('dates_helper', 'send_message_helper'));
    }
    
    public function update_comment()
    {
    //    echo $this->input->post('id');
    //    echo $this->input->post('text');
        $this->load->model('comments_model');
        $data = array('comment_text'=>$this->input->post('text'));
        $this->comments_model->updateComment($data,$this->input->post('id'));
    }

    public function delete_booking_ajax($bookingId) {
        if(empty($bookingId)){
            $data = array(
                'error' => 1,
                'text' => 'This booking is not valid'
            );
            $this->load->view('merchant/echodata', array('data' => json_encode($data)));
            return;
        }

        $res = $this->bookings_model->cancelBooking($bookingId);
        if($res){
            $bookingData = $this->notifications_model->getBookingDataByBookingId($bookingId);
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

    public function update_booking_ajax($bookingId = null) {
        if(empty($bookingId)){
            $data = array(
                'error' => 1,
                'text' => 'This booking is not valid'
            );
            $this->load->view('merchant/echodata', array('data' => json_encode($data)));
            return;
        }

        $this->data['bookingData'] = $this->bookings_model->get($bookingId);

        if(empty($this->data['bookingData'])){
            $data = array(
                'error' => 1,
                'text' => 'This booking is not valid'
            );
            $this->load->view('merchant/echodata', array('data' => json_encode($data)));
            return;
        }
        $this->data['date'] = $this->input->post('date', true);
        $this->data['time'] = $this->input->post('time', true);
        $this->data['duration'] = $this->input->post('duration', true);
        $this->data['description'] = $this->input->post('comment', true);
        $dateArr = explode('/',$this->data['date']);
        $time = date('H:i:s', strtotime($this->data['time']));
        $this->data['duration'] = preg_replace('/[^0-9\:]/', '', $this->data['duration']);
        $from = $dateArr[2].'-'.$dateArr[1].'-'.$dateArr[0] . ' ' . $time;
        $updateBookingsData = array(
            'from' => $from,
            'to' => _getDateTo($from, $this->data['duration']),
            'duration' => $this->data['duration'],
            'description' => $this->data['description']
        );
        $intersectedInterval = false;
        $roomBookings = $this->bookings_model->getRoomBookings($this->data['bookingData']->room_id, $this->data['date'], $bookingId);

        foreach ($roomBookings as $v){
            if(!checkBookingInterval($v['from'], $v['duration'], $v['to'], $updateBookingsData['from'], $updateBookingsData['to'])){
                $intersectedInterval = true;
                break;
            }
        }

        if($intersectedInterval){
            $data = array(
                'error' => 1,
                'text' => 'Cannot edit thist booking time intersection'
            );
            $this->load->view('merchant/echodata', array('data' => json_encode($data)));
            return;
        }

        $res = $this->bookings_model->update($this->data['bookingData']->id, $updateBookingsData);
        
        if($res){
            
            $bookingData = $this->notifications_model->getBookingDataByBookingId($this->data['bookingData']->id);
            if (isset($bookingData) && !empty($bookingData['user_id']))
            {
                $notificationData = array(
                        'user_id' => $bookingData['user_id'],
                        'booking_id' => $bookingData['booking_id'],
                        'type' => 5,
                        'created_at' => date('Y-m-d H:i:s')
                    );
                notify($notificationData, $bookingData);
            }
            
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

    public function get_companies_by_location_id_ajax($locationId = null) {
        $companiesList = $this->companies_model->getCompaniesListDD($locationId);
        $this->data['companiesList'] = form_dropdown('company_id', $companiesList, set_value('company_id', 0), 'id="company_id"');

        $data = array(
            'success' => 1,
            'html' => $this->load->view('admin/booking/add_booking/companies_list', array('companiesList' => $this->data['companiesList']), true)
        );

        $this->load->view('merchant/echodata', array('data' => json_encode($data)));
    }

    public function get_users_by_company_id_ajax($companyId = null) {
        $usersList = $this->users_model->getUserListDD(null, $companyId);
        $this->data['usersList'] = form_dropdown('user_id', $usersList, set_value('user_id', 0), 'id="user_id"');
        $data = array(
            'success' => 1,
            'html' => $this->load->view('admin/booking/add_booking/users_list', array('usersList' => $this->data['usersList']), true)
        );

        $this->load->view('merchant/echodata', array('data' => json_encode($data)));
    }

    public function get_for_users_by_company_id_ajax($companyId = null, $userId = null) {
        $forUsersList = $this->users_model->getUserListDD($userId, $companyId);
        $this->data['forUsersList'] = form_dropdown('for_user_id', $forUsersList, set_value('for_user_id', 0), 'id="for_user_id"');
        $data = array(
            'success' => 1,
            'html' => $this->load->view('admin/booking/add_booking/for_users_list', array('forUsersList' => $this->data['forUsersList']), true)
        );

        $this->load->view('merchant/echodata', array('data' => json_encode($data)));
    }

    public function get_rooms_by_location_id_ajax($locationId = null) {
        $roomsList = $this->rooms_model->getRoomsListDD($locationId);
        $this->data['roomsList'] = form_dropdown('room_id', $roomsList, set_value('room_id', 0), 'id="room_id"');
        $data = array(
            'success' => 1,
            'html' => $this->load->view('admin/booking/add_booking/rooms_list', array('roomsList' => $this->data['roomsList']), true)
        );

        $this->load->view('merchant/echodata', array('data' => json_encode($data)));
    }

    public function send_email() {
        $toArr = array();
        $this->_setValidationRules('send_email');
        if($this->input->post()){
            if($this->form_validation->run() == true){
                $this->_getDataFromForm('send_email');
                switch ($this->data['emailType']) {
                    case 'user_email':
                        $message = $this->load->view('emails/user_email',$this->data,true);
                        $subject = $this->data['emailSubject'];
                        $toArr = $this->users_model->getUsersByIdsArr($this->data['idsArr']);
                        break;
                }
                if(!empty($toArr)){
                    foreach ($toArr as $v){
                        $res = _sendEmail($this->config->item('admin_notication_email'), $v['email'], $message, $subject);
                    }
                }
                if(isset($res) && !empty($res)){
                    $data = array(
                        'success' => 1,
                    );
                }
                else {
                    $data = array(
                        'error' => 1,
                        'text' => 'Email send error'
                    );
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
                return;
            }
        }
    }

    private function _setValidationRules($type, $id = null) {
        switch ($type) {
            case 'send_email':
                $this->form_validation->set_rules('email_text', 'Email text', 'required|xss_clean|trim');
                $this->form_validation->set_rules('email_subject', 'Email Subject', 'required|xss_clean|trim');
                break;
        }
    }

    private function _getDataFromForm($type) {
        switch ($type) {
            case 'send_email':
                $this->data['idsArr'] = $this->input->post('idsArr', true);
                $this->data['emailText'] = $this->input->post('email_text', true);
                $this->data['emailSubject'] = $this->input->post('email_subject', true);
                $this->data['emailType'] = $this->input->post('type', true);
                break;
        }
    }

}

?>
