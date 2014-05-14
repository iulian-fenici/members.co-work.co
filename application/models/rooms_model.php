<?php

class Rooms_model extends MY_Model {

    public $_table = 'rooms';

    public function __construct() {
        parent::__construct();
    }

    public function getRoomsList($filters = null, $orderByArr = array('r.id' => 'ASC'), $limit = 0, $offset = 0) {
        $this->apply_filters($filters, 'rooms_list');
        $this->apply_order($orderByArr, 'rooms_list');
        $res = $this->db->select('r.*, l.name as location_name,l.id as location_id,COUNT(b.id) as bookings_count')
            ->from('rooms r')
            ->join('locations l', 'l.id = r.location_id', 'left')
            ->join('bookings b', 'b.room_id = r.id', 'left')
            ->group_by('r.id');
        return $res->get()->result_array();
    }

    public function getRoomsByLocationId($locationId) {
        $res = $this->db->select('r.*, l.name as location_name')
            ->from('rooms r')
            ->join('locations l', 'l.id = r.location_id', 'left')
            ->where('l.id', $locationId)
            ->group_by('r.id');
        return $res->get()->result_array();
    }
    
    public function getRoomsByNameLocationId($roomName, $locationId) {
        $res = $this->db->select('r.*, l.name as location_name')
            ->from('rooms r')
            ->join('locations l', 'l.id = r.location_id', 'left')
            ->where('l.id', $locationId)
            ->where('r.name', $roomName)
            ->group_by('r.id');
        return $res->get()->row_array();
    }
    
    public function getRoomsListDD($locationId = null) {
        if($locationId){
            $this->db->where('r.location_id =', $locationId);
        }
        $rooms = $this->db->select('r.id, r.name')
            ->from('rooms r')
            ->get()
            ->result_array();

        $result = array('' => 'Select Room');
        foreach ($rooms as $room){
            $result[$room['id']] = $room['name'];
        }

        return $result;
    }

    public function getCountRooms($filters = null, $orderByArr = array()) {
        $this->apply_filters($filters, 'rooms_list');
        $this->apply_order($orderByArr, 'rooms_list');
        $res = $this->db->select('COUNT(r.id)')
            ->from('rooms r')
            ->join('locations l', 'l.id = r.location_id', 'left')
            ->group_by('r.id');
        $res = $this->db->get()->row_array();

        return isset($res['count']) && !empty($res['count']) ? $res['count'] : false;
    }

    public function getLocationByRoomId($roomId) {
        $res = $this->db->select('l.*')
            ->from('locations l')
            ->join('rooms r', 'r.location_id = l.id', 'inner')
            ->where('r.id',$roomId)
            ->group_by('l.id');
        return $res->get()->row_array();
    }
}
