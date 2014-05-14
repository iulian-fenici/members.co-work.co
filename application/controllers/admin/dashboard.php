<?php

if(!defined('BASEPATH'))
    exit('No direct script access allowed');
//Admin panel 
class Dashboard extends My_Controller {

    public function __construct() {
        parent::__construct();
        $this->checkAdminLoggedIn();
        $this->load->model(array('users_model', 'bookings_model'));
        $this->load->helper(array('dates_helper','color_helper'));
    }

    public function index() {
        $this->data = array();

        //view merchant
        $this->viewData['header'] = $this->load->view('admin/main/header', $this->data,true);
        $this->viewData['menu'] = $this->load->view('admin/main/menu', $this->data,true);
        $this->viewData['sidebar'] = $this->load->view('admin/main/sidebar', $this->data,true);
        $this->viewData['message'] = $this->load->view('admin/main/message', $this->data,true);
        $this->viewData['content'] = $this->load->view('admin/dashboard', $this->data,true);
        $this->viewData['footer'] = $this->load->view('admin/main/footer', $this->data,true);
        $this->load->view('admin/main/content', $this->viewData);
    }
    public function month() {
        $this->data = array();

        //view merchant
        $this->viewData['header'] = $this->load->view('admin/main/header', $this->data,true);
        $this->viewData['menu'] = $this->load->view('admin/main/menu', $this->data,true);
        $this->viewData['sidebar'] = $this->load->view('admin/main/sidebar', $this->data,true);
        $this->viewData['message'] = $this->load->view('admin/main/message', $this->data,true);
        $this->viewData['content'] = $this->load->view('admin/month_view', $this->data,true);
        $this->viewData['footer'] = $this->load->view('admin/main/footer', $this->data,true);
        $this->load->view('admin/main/content', $this->viewData);
    }
    
    public function get_calendar(){
        $date = date('Y-m-d', strtotime($this->input->post('date')));
        
        $roomBookings = $this->bookings_model->getRoomBookingsByDate(null,$date);
        
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
                        'event_url' => '/admin/booking/edit_booking/'.$booking['id'],
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
        $roomBookings = $this->bookings_model->getRoomBookingsByRoom(null,$date);
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
