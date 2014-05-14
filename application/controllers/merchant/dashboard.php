<?php

if(!defined('BASEPATH'))
    exit('No direct script access allowed');

class Dashboard extends MY_Controller {

    public function __construct() {
        //var_dump($this->session->all_userdata());
        parent::__construct();       
        $this->checkLoggedIn();
        $this->load->model(array('users_model', 'bookings_model','locations_model'));
        $this->load->helper(array('dates_helper','date','announcements','color_helper'));
    }

    public function index() {
        $this->data = array();
        $this->data['user'] = $this->users_model->get($this->session->userdata('user_id'));     
        
        if($this->session->userdata('cancel_booking')){
            $this->data['cancel_booking'] = $this->session->userdata('cancel_booking');
            $bookingData = $this->bookings_model->get($this->data['cancel_booking']['booking_id']);
            if(empty($bookingData) || $bookingData->user_id != $this->data['cancel_booking']['user_id']){                
                unset($this->data['cancel_booking']);
                
            }else{
                $nowDate = new DateTime();
                $bookingDate = new DateTime($bookingData->from);
                $interval = $nowDate->diff($bookingDate);
                $hours = $interval->format('%h'); 
                if($hours<=2){
                    unset($this->data['cancel_booking']);
                    $this->data['cancel_booking_error']='Your link is expired';
                }
            }
            $this->session->unset_userdata('cancel_booking');
        }
        $this->data['bookings'] = $this->bookings_model->getCompanyBookings($this->session->userdata('company_id'));
        $userLocationsIds = $this->locations_model->getLocationsVariableByCompanyId($this->session->userdata('company_id'),'id');
        $this->data['announcements'] = getAnnouncements('dashboard',array('user_id'=>$this->session->userdata('user_id'),'company_id'=>$this->session->userdata('company_id'),'location_ids'=>$userLocationsIds));
        
        //view merchant
        $this->viewData['header'] = $this->load->view('merchant/main/header', $this->data,true);
        $this->viewData['menu'] = $this->load->view('merchant/main/menu', $this->data,true);
        $this->viewData['sidebar'] = $this->load->view('merchant/main/sidebar', $this->data,true);
        $this->viewData['message'] = $this->load->view('merchant/main/message', $this->data,true);
        $this->viewData['content'] = $this->load->view('merchant/dashboard', $this->data,true);
        $this->viewData['footer'] = $this->load->view('merchant/main/footer', $this->data,true);
        $this->load->view('merchant/main/content', $this->viewData);
    }       
    public function month() {
        $this->data = array();

        //view merchant
        $this->viewData['header'] = $this->load->view('merchant/main/header', $this->data,true);
        $this->viewData['menu'] = $this->load->view('merchant/main/menu', $this->data,true);
        $this->viewData['sidebar'] = $this->load->view('merchant/main/sidebar', $this->data,true);
        $this->viewData['message'] = $this->load->view('merchant/main/message', $this->data,true);
        $this->viewData['content'] = $this->load->view('merchant/month_view', $this->data,true);
        $this->viewData['footer'] = $this->load->view('merchant/main/footer', $this->data,true);
        $this->load->view('merchant/main/content', $this->viewData);
    }
    
    public function get_calendar(){
        $date = $this->input->post('date');
        $userLocationsIds = $this->locations_model->getLocationsVariableByCompanyId($this->session->userdata('company_id'),'id');
        $roomBookings = $this->bookings_model->getRoomBookingsByDate($userLocationsIds,$date);

        foreach($roomBookings as $k => $room)
        {   
            if ($room['bookings'])
            {
                $res=array();
                $colorInd = 0;
                foreach ($room['bookings'] as $key => $booking)
                {
                    if($colorInd == 2){
                        $colorInd = 0;
                    }

                    $res[] = array(
                        'title' => $booking['description'],
                        'start' => isset($booking['from'])&&!empty($booking['from'])?date('Y-m-d H:i:s',strtotime($booking['from'])):'',
                        'end' => _getDateTo(date('Y-m-d H:i:s',strtotime($booking['from'])),$booking['duration']),
                        'allDay' => false,
                        //'backgroundColor'=> '#'.dechex(rand(0, 255)).dechex(rand(0, 255)).dechex(rand(0, 255)),
                        'backgroundColor'=>'#'.getEventColor($colorInd),
                        'borderColor'=>'#'.getEventColor($colorInd),
                        'company'=>$booking['company_name'],
                        'username' => $booking['username'],
                        'foruser' => $booking['foruser'],
                        'event_url' => $booking['user_id'] == $this->session->userdata('user_id')?'/merchant/booking/edit_booking/'.$booking['id']:'',
                    );
                    $colorInd++;
                }
                $roomBookings[$k]['bookings'] = $res;
            }
            else {
                $roomBookings[$k]['bookings'] = array();
            }
        }

        $data = array(
                'success' => 1,
                'rooms' => $roomBookings
        );
        $this->load->view('merchant/echodata', array('data' => json_encode($data)));
    }
    public function get_month_calendar(){
        $date = $this->input->post('date');     
        $userLocationsIds = $this->locations_model->getLocationsVariableByCompanyId($this->session->userdata('company_id'),'id');
        $roomBookings = $this->bookings_model->getRoomBookingsByRoom($userLocationsIds,$date);
        $res=array();
        $colorInd = 0;
        foreach($roomBookings as $k => $booking)
        {
            
            if($colorInd == 2){
                $colorInd = 0;
            }

            $res[] = array(
                'title' => $booking['room_name'].' '.'('.$booking['bookigns_count'].')',
                'start' => isset($booking['booking_date'])&&!empty($booking['booking_date'])?date('Y-m-d H:i:s',strtotime($booking['booking_date'])):'',
                'end' => _getDateTo(date('Y-m-d H:i:s',strtotime($booking['booking_date'])),'01:00'),
                'allDay' => false,
                'backgroundColor'=> '#'.dechex(rand(0, 255)).dechex(rand(0, 255)).dechex(rand(0, 255)),
                'backgroundColor'=>'#'.getEventColor($colorInd),
                'borderColor'=>'#'.getEventColor($colorInd),
                'count'=>$booking['bookigns_count'],
                'room_name'=>$booking['room_name'],
                'location_name'=>$booking['location_name'],
            );
            $colorInd++;
        }
        $data = array(
                'success' => 1,
                'rooms' => $res
        );
        $this->load->view('merchant/echodata', array('data' => json_encode($data)));
    }
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */