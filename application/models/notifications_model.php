<?php

class Notifications_model extends MY_Model {

    public $_table = 'notifications';

    public function __construct() {
        parent::__construct();
    }
    
    public function getUserListForBookingNotification()
    {
        $nowObj = new DateTime;
        $now = $nowObj->format('Y-m-d H:i:s');
        $res = $this->db->select('b.id as booking_id, b.from, b.to, u.id as user_id, CONCAT(u.first_name,\' \',u.last_name) as username, u.email, n.id, r.name as room_name, l.name as location_name',false)
                        ->from('bookings b')
                        ->join('users u', 'u.id = b.user_id', 'left')
                        ->join('notifications n', 'n.booking_id = b.id and n.type = 1', 'left')
                        ->join('locations l', 'l.id = b.location_id')
                        ->join('rooms r', 'r.id = b.room_id')
                        ->where('b.from - INTERVAL 2 hour <=', $now)
                        ->where('b.from >', $now)
                        ->where('n.id is NULL')
                        ->get()
                        ->result_array();
        //echo $this->db->last_query();
        //log_message('error', "Notification. getUserListForBookingNotification=".print_r( $this->db->last_query(), true));
        return isset($res) && !empty($res)?$res: false;
        
    }
    
        public function getBookingDataByBookingId($bookingId)
    {
        $res = $this->db->select('b.id as booking_id, b.from,b.duration,b.to, u.id as user_id, CONCAT(u.first_name,\' \',u.last_name) as username, u.email, r.name as room_name, l.name as location_name',false)
                        ->from('bookings b')
                        ->join('users u', 'u.id = b.user_id', 'left')
                        ->join('locations l', 'l.id = b.location_id')
                        ->join('rooms r', 'r.id = b.room_id')
                        ->where('b.id', $bookingId)
                        ->get()
                        ->row_array();
        return isset($res) && !empty($res)?$res: false;
    }
    
    public function deleteNotificationsByUserId($userId)
    {
        $this->db->where('user_id', $userId);
        $this->db->delete('notifications');
        return $this->db->affected_rows();
    }
    
}