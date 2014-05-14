<?php
 
if(!defined('BASEPATH'))
    exit('No direct script access allowed');

class Stats_model extends MY_Model {

    public $_table = 'stats';

    public function __construct() {
        parent::__construct();
    }

    public function getUserStatisticInfo($userId) {
        $this->load->helper('dates_helper');
        $results['bookingSeconds'] = $this->db->select('SUM(TIME_TO_SEC(TIMEDIFF(b.to, b.from))) as count', false)
            ->from('bookings b ')
            ->join('rooms r', 'r.id = b.room_id')
            ->join('users u', 'u.id = b.user_id')
            ->where('u.id',$userId)
            ->group_by('b.user_id')
            ->get()
            ->row_array();
        if(isset($results['bookingSeconds']['count'])){
            $results['bookingSeconds'] = convertTime($results['bookingSeconds']['count']/3600);
        }else{
            $results['bookingSeconds']=array('h'=>0,'m'=>0);
        }
        $results['bookingCount'] = $this->db->select('count(b.id) as count', false)
            ->from('bookings b ')
            ->join('rooms r', 'r.id = b.room_id')
            ->join('users u', 'u.id = b.user_id')
            ->where('u.id',$userId)
            ->group_by('b.user_id')
            ->get()
            ->row_array();
        if(isset($results['bookingCount']['count'])){
            $results['bookingCount'] = $results['bookingCount']['count'];
        }else{
            $results['bookingCount']=0;
        }
        return $results;
    }
    public function getCompanyStatisticInfo($companyId) {
        $this->load->helper('dates_helper');
        $results['bookingSeconds'] = $this->db->select('SUM(TIME_TO_SEC(TIMEDIFF(b.to, b.from))) as count', false)
            ->from('bookings b ')
            ->join('rooms r', 'r.id = b.room_id')
            ->join('companies c', 'c.id = b.company_id')
            ->where('c.id',$companyId)
            ->group_by('b.company_id')
            ->get()
            ->row_array();
        if(isset($results['bookingSeconds']['count'])){
            $results['bookingSeconds'] = convertTime($results['bookingSeconds']['count']/3600);
        }else{
            $results['bookingSeconds']=array('h'=>0,'m'=>0);
        }
        $results['bookingCount'] = $this->db->select('count(b.id) as count', false)
            ->from('bookings b ')
            ->join('rooms r', 'r.id = b.room_id')
            ->join('companies c', 'c.id = b.company_id')
            ->where('c.id',$companyId)
            ->group_by('b.company_id')
            ->get()
            ->row_array();
        if(isset($results['bookingCount']['count'])){
            $results['bookingCount'] = $results['bookingCount']['count'];
        }else{
            $results['bookingCount']=0;
        }
        return $results;
    }

    public function getBookingBy($table, $bookingColumn, $dateInterval) {
        $resultArr = array();
        if($table == 'users'){
            $name_col = 'username';
            $this->db->where('id !=', 1);
        }
        else {
            $name_col = 'name';
        }
        $tableByData = $this->db->select("id, $name_col")
            ->from("$table")
            ->get()
            ->result_array();

        $rooms = $this->db->select('r.id as room_id, r.name as room_name')
            ->from('rooms r')
            ->get()
            ->result_array();

        foreach ($rooms as $room){
            $names[] = $room['room_name'];
        }

        foreach ($tableByData as $item){
            $categories[] = $item[$name_col];

            foreach ($rooms as $room){
                if($dateInterval['resultType'] == 'time')
                    $count = (int) $this->countBookingsBy($table, $bookingColumn, $item['id'], $room['room_id'], $dateInterval) / 3600;
                else
                    $count = (int) $this->countBookingsBy($table, $bookingColumn, $item['id'], $room['room_id'], $dateInterval);
                $roomArr[$item['id']][$room['room_name']] = $count;
            }
        }
        foreach ($roomArr as $k => $v){
            foreach ($v as $k1 => $v1){
                $res[$k1][] = $v1;
            }
        }
        foreach ($res as $k => $v){
            $series[] = array(
                'name' => $k,
                'data' => $v
            );
        }
        
        foreach ($categories as $key=>$val)
        {
            $notempty = false;
            foreach ($series as $k=>$v)
            {
                if($v['data'][$key]!=0)
                {
                    $notempty = true;
                }
            }
            if ($notempty==false)
            {
                unset($categories[$key]);
                foreach ($series as $k=>$v)
                {
                    unset($series[$k]['data'][$key]);
                }
               
            }                
        }
        
        $i=0;
        foreach ($categories as $key=>$val)
        {
            $categories[$i]=$val;
            foreach ($series as $k=>$v)
                {                    
                    $series[$k]['data'][$i] = $series[$k]['data'][$key];
                    unset($series[$k]['data'][$key]);
                }
                $i++;
        }
       
        $resultArr['categories'] = $categories;
        $resultArr['series'] = $series;

        return $resultArr;
    }

    private function countBookingsBy($table, $bookingColumn, $columnVal, $roomId, $dateInterval) {
        if($dateInterval){
            //var_dump($dateInterval);
            if($dateInterval['dateFrom'] && !$dateInterval['dateTo'])//only date from
                $this->db->where('b.from>="', $dateInterval['dateFrom'] . '"', false);
            else if(!$dateInterval['dateFrom'] && $dateInterval['dateTo'])//only date to
                $this->db->where('b.from<="', $dateInterval['dateTo'] . '"');
            else if($dateInterval['dateFrom'] && $dateInterval['dateTo'])//date from and date to  
                $this->db->where('b.from >= "' . $dateInterval['dateFrom'] . '" AND b.from <= "' . $dateInterval['dateTo'] . '"');
        }

        if(!isset($dateInterval['resultType']) || !$dateInterval['resultType'] || $dateInterval['resultType'] == 'time')
            $this->db->select('SUM(TIME_TO_SEC(TIMEDIFF(b.to, b.from))) as time', false);
        else {
            $this->db->select('count(b.id) as time', false);
        }
        $this->db->from('bookings b ')
            ->join('rooms r', 'r.id = b.room_id', 'left')
            ->join("$table t", "t.id = b.$bookingColumn")
            ->where("b.$bookingColumn", $columnVal)
            ->where('r.id', $roomId)
            ->group_by('r.id');
        $result = $this->db->get()->row_array();
        //echo $this->db->last_query();
        return isset($result['time']) && !empty($result['time']) ? $result['time'] : 0;
    }

}
