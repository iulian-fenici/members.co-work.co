<?php

class Bookings_model extends MY_Model {

    public $_table = 'bookings';

    public function __construct() {
        parent::__construct();
    }

    public function getCompanyBookings($companyId) {
        $res = $this->db->select('b.*, c.name as company_name, l.name as location_name, u.username as user_name, CONCAT(u1.first_name,\' \',u1.last_name) as foruser, r.name as room_name', false)
                ->from('bookings b')
                ->join('companies c ', 'b.company_id = c.id', 'left')
                ->join('company_location cl','cl.company_id = c.id','inner')                
                ->join('locations l','l.id = b.location_id','left')
                ->join('users u', 'u.id = b.user_id', 'left')
                ->join('users u1', 'u1.id = b.for_user_id', 'left')
                ->join('rooms r', 'r.id = b.room_id', 'left')
                ->where('b.company_id', $companyId)
                ->order_by('b.from','desc')
                ->group_by('b.id')
                ->get()
                ->result_array();
        return isset($res) && !empty($res) ? $res : false;
    }
    
    public function getUserBookings($userId) {
        $res = $this->db->select('b.*, c.name as company_name, l.name as location_name, u.username as user_name, CONCAT(u1.first_name,\' \',u1.last_name) as foruser, r.name as room_name', false)
                ->from('bookings b')
                ->join('companies c ', 'b.company_id = c.id', 'left')
                ->join('company_location cl','cl.company_id = c.id','inner')
                ->join('locations l','l.id = cl.location_id','left')
                ->join('users u', 'u.id = b.user_id', 'left')
                ->join('users u1', 'u1.id = b.for_user_id', 'left')
                ->join('rooms r', 'r.id = b.room_id', 'left')
                ->where('u.id', $userId)
                ->get()
                ->result_array();

        return isset($res) && !empty($res) ? $res : false;
    }
    
    public function getRoomBookingsByDate($locationIds = null, $date, $company_id = null,$existBookingId = null) {
        $locationRooms = array();
        $locationRooms = $this->getLocationRooms($locationIds);
        foreach ($locationRooms as $k => $room) {
            $locationRooms[$k]['bookings'] = $this->getRoomBookings($room['room_id'], $date,$existBookingId, $company_id);
        }
        return $locationRooms;
    }
    
    
    public function getRoomBookingsByRoom($locationIds = null, $date) {
        $date = str_replace('/', '-', $date);
        $month = (int)date('m',strtotime($date));
        $year = (int)date('Y',strtotime($date));
        if ($locationIds) {
            $this->db->where_in('b.location_id', $locationIds);
        }
        $res = $this->db->select('DATE(b.from)as booking_date,COUNT(*) as bookigns_count,b.room_id,r.name as room_name,l.name as location_name')
                ->from('bookings b')
                ->join('rooms r','r.id = b.room_id','inner')
                ->join('locations l','l.id = r.location_id','inner')
                ->where('MONTH(b.from)',$month)
                ->where('YEAR(b.from)',$year)
                ->group_by('DATE(b.from)')
                ->group_by('b.room_id')
                ->get()
                ->result_array();
       //echo $this->db->last_query();
        return $res;
    }
    

    public function cancelBooking($bookingId) {
        $this->db->where('id', $bookingId);
        $this->db->delete('bookings');
        return $this->db->affected_rows();
    }

    public function getLocationRooms($locationIds = null) {
        if ($locationIds) {
            $this->db->where_in('r.location_id', $locationIds);
        }

        $locationRooms = $this->db->select('r.id as room_id,r.location_id,r.big_name as room_big_name,r.big_rel_path as room_big_rel_path,r.thumb_name as room_thumb_name,r.thumb_rel_path as room_thumb_rel_path,r.thumb_abs_path as room_thumb_abs_path, r.name as room_name, l.name as location_name, c.name as company_name')
                ->from('rooms r')
                ->join('locations l', 'l.id = r.location_id', 'left')
                ->join('company_location cl','cl.location_id = r.location_id','left')
                ->join('companies c', 'c.id = cl.company_id', 'left')
                ->group_by('r.id')
                ->order_by('l.id,r.id')
                ->get()
                ->result_array();
        return isset($locationRooms) ? $locationRooms : false;
    }

    public function getRoomBookings($roomId, $date, $bookingId = null, $company_id=null) {
        
        $date = str_replace('/', '-', $date);
        $date = date('Y-m-d', strtotime($date));
        
        if ($bookingId) {
            $this->db->where('b.id !=', $bookingId);
        }
        if (!empty($company_id)){
            $this->db->where('c.id =', $company_id);
        }
        $res = $this->db->select('b.*, c.name as company_name, CONCAT(u.first_name,\' \',u.last_name) as username, CONCAT(u1.first_name,\' \',u1.last_name) as foruser', false)
                ->from('bookings b')
                ->join('companies c ', 'b.company_id = c.id', 'left')
                ->join('users u', 'u.id = b.user_id', 'left')
                ->join('users u1', 'u1.id = b.for_user_id', 'left')
                ->where('b.room_id', $roomId)
                ->where('DATE(b.from)', $date)
                ->group_by('b.id')
                ->get()
                ->result_array();
        return $res;
    }

    public function deleteUsersBookings($userId) {
        $this->db->where('user_id', $userId);
        $this->db->delete('bookings');
        return $this->db->affected_rows();
    }

    public function getBookingsList($filters = null, $orderByArr = array('b.from' => 'ASC','c.name' => 'ASC', 'r.name' => 'ASC' ), $limit = 0, $offset = 0) {
        $this->apply_filters($filters, 'booking_list');
        $this->apply_order($orderByArr, 'booking_list');
        $res = $this->db->select('b.*, c.id as company_id, r.name as room_name, c.name as company_name, l.name as location_name, u.username as user_name, u1.username as foruser, r.name as room_name', false)
                ->from('bookings b')
                ->join('companies c ', 'b.company_id = c.id', 'left')
                ->join('company_location cl','cl.company_id = c.id','inner')
                ->join('locations l','l.id = cl.location_id','left')
                ->join('users u', 'u.id = b.user_id', 'left')
                ->join('users u1', 'u1.id = b.for_user_id', 'left')
                ->join('rooms r', 'r.id = b.room_id', 'left');
                //->order_by('b.from, r.name DESC');

        if (!empty($limit)) {
            if (!empty($offset)) {
                $this->db->limit($limit, $offset);
            } else {
                $this->db->limit($limit);
            }
        }
        $res = $this->db->get()->result_array();
        //echo '<pre>'.$this->db->last_query().'</pre>';die();
        return isset($res) && !empty($res) ? $res : false;
    }
    
     public function getCountBookings($filters = null, $orderByArr = array()) {
        $this->apply_filters($filters, 'booking_list');
        $this->apply_order($orderByArr, 'booking_list');
        $res = $this->db->select('COUNT(b.id) as count', false)
                ->from('bookings b')
                ->join('companies c ', 'b.company_id = c.id', 'left')
                ->join('company_location cl','cl.company_id = c.id','inner')
                ->join('locations l','l.id = cl.location_id','left')
                ->join('users u', 'u.id = b.user_id', 'left')
                ->join('users u1', 'u1.id = b.for_user_id', 'left')
                ->join('rooms r', 'r.id = b.room_id', 'left');

        $res = $this->db->get()->row_array();

        return isset($res['count']) && !empty($res['count']) ? $res['count'] : false;
    }
    
    public function deleteBookingsByUserId($userId)
    {
        $this->db->where('user_id', $userId);
        $this->db->delete('bookings');
        return $this->db->affected_rows();
    }
    
    public function getUserBookingById($bookingId) {
        $res = $this->db->select('b.*, c.name as company_name, c.id as company_id, l.id as location_id, l.name as location_name, u.username as user_name, CONCAT(u1.first_name,\' \',u1.last_name) as foruser, r.id as room_id, r.name as room_name', false)
                ->from('bookings b')
                ->join('companies c ', 'b.company_id = c.id', 'left')
                ->join('company_location cl','cl.company_id = c.id','inner')
                ->join('locations l','l.id = cl.location_id','left')
                ->join('users u', 'u.id = b.user_id', 'left')
                ->join('users u1', 'u1.id = b.for_user_id', 'left')
                ->join('rooms r', 'r.id = b.room_id', 'left')
                ->where('b.id', $bookingId)
                ->get()
                ->result_array();

        return isset($res) && !empty($res) ? $res : false;
    }

    public function getUserBooking($bookingId, $userId)
    {
        $res = $this->db->select()
                        ->from ('bookings b')
                        ->where('b.id', $bookingId)
                        ->where('b.user_id', $userId)
                        ->get()
                        ->row();
        return $res;
    }
}