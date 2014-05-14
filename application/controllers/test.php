<?php

if(!defined('BASEPATH'))
    exit('No direct script access allowed');

class Test extends My_Controller {
    
    function getMultiCompaniesAlter(){
        $coworkdb = $this->load->database('default', TRUE );
        $companies = $coworkdb->select('id,location_id')
                ->from('companies')
                ->get()
                ->result_array();
        
        foreach($companies as $v){
            echo 'INSERT INTO `company_location` (`company_id`, `location_id`) VALUES ('.$v['id'].','.$v['location_id'].');'."\n";
        }
    }
    
    public function testServerTime(){
        echo date('Y-m-d H:i:s')."\n";
        echo date('P');
    }
    
    function mail(){
        $this->load->helper('send_message_helper');
        $res = _sendEmail('test@co-work.co', 'tatarenkoigor@gmail.com', 'test', 'test');
        var_dump($res);
    }
    
    function export_bookings(){
        $coworkdb = $this->load->database('cowork', TRUE );
               
        $bookings = $coworkdb->select('n.nid,n.title as booking_title,u.mail as user_email,cfd.field_date_value as \'from\',cfd.field_date_value2 as \'to\',n2.title as company,n3.title as room,n4.title as location',false)
                ->from('node n')
                ->join('users u','u.uid=n.uid','inner')
                ->join('content_field_date cfd','cfd.nid=n.nid','inner')
                ->join('content_type_meeting_room_booking ctmrb','ctmrb.vid=n.vid','inner')
                ->join('node n2','n2.nid = ctmrb.field_company_nid AND n2.type="company"','left')
                ->join('node n3','n3.nid = ctmrb.field_room_nid AND n3.type="meeting_room"','left')
                ->join('og_ancestry oa','oa.nid = n3.nid','left')
                ->join('node n4','n4.nid = oa.group_nid AND n4.type="group"','left')                
                ->where('n.type','meeting_room_booking')
                ->where('n.uid !=',0)
//                ->where('n2.title is not null')
                //->limit(50)
                ->group_by('n.nid')
                ->get()
                ->result_array();
        
//        debug($bookings);die();
        $lbmbookingsdb = $this->load->database('default', TRUE );
         
        if(!empty($bookings)){
            foreach($bookings as $key=>$booking){
                if(empty($booking['company'])){
                    $bookings[$key]['company'] = $this->_explodeBookingTitlte($booking['booking_title']);
                }
                if(empty($booking['company'])){
                    unset($bookings[$key]);
                    continue;
                }
                //get company by name
                $bookings[$key]['companyId'] = $this->_getCompanyByName($booking['company']);
                if(empty($bookings[$key]['companyId'])){
                    unset($bookings[$key]);
                    continue;
                }
                //get user by email
                $bookings[$key]['userId'] = $this->_getUserByName($booking['user_email'],$bookings[$key]['companyId']);
                if(empty($bookings[$key]['userId'])){
                    unset($bookings[$key]);
                    continue;
                }
                //get location by name
                $bookings[$key]['locationId'] = $this->_getLocationByName($booking['location'],$bookings[$key]['companyId']);
                if(empty($bookings[$key]['locationId'])){
                    unset($bookings[$key]);
                    continue;
                }
                //get room by name
                $bookings[$key]['roomId'] = $this->_getRoomByName($booking['room'],$bookings[$key]['locationId']);
                if(empty($bookings[$key]['roomId'])){
                    unset($bookings[$key]);
                    continue;
                }
                
                //add bookings to db
                $insertBookingsData = array(
                    'location_id' => $bookings[$key]['locationId'],
                    'company_id' => $bookings[$key]['companyId'],
                    'user_id' => $bookings[$key]['userId'],
                    'room_id' => $bookings[$key]['roomId'],
                    'from' => date('Y-m-d H:i:s', strtotime($booking['from'])),
                    'to' => date('Y-m-d H:i:s', strtotime($booking['to'])),
                    'description' => '',
                    'imported'=>1
                ); 
                
                $diff = strtotime($booking['to']) - strtotime($booking['from']);
                if($diff<0){
                    $diff=$diff*(-1);
                }
                $diff = $diff/3600;
                
                $hour =(int) floor($diff);                
                $min = (int) round(60*($diff - $hour));
                if($hour<10){
                    $hour = '0'.$hour;
                }
                if($min<10){
                    $min = '0'.$min;
                }                
                $insertBookingsData['duration'] = $hour.':'.$min;
                $bookingId = $lbmbookingsdb->insert('bookings',$insertBookingsData);
                
            }
        }
    }
    private function _getCompanyByName($companyName){
        $this->load->database('default', TRUE );
        $this->load->model('companies_model');
        return $this->companies_model->getCompanyIdByName($companyName);
    }
    private function _getlocationByName($locationName,$companyId){
        $this->load->database('default', TRUE );
        $this->load->model(array('locations_model','companies_model'));
        $locationId = $this->locations_model->getLocationIdByName($locationName);
        if(!empty($locationId)){
            $locations = $this->locations_model->getLocationsVariableByCompanyId($companyId,'id');
            if(in_array($locationId, $locations)){
                return $locationId;
            }
        }
        return false;
    }
    private function _getRoomByName($roomName,$locationId){
        $this->load->database('default', TRUE );
        $this->load->model('rooms_model');
        $room =  $this->rooms_model->getRoomsByNameLocationId($roomName,$locationId);
        if(!empty($room)){
            return $room['id'];
        }
        return false;
    }
    private function _getUserByName($email,$companyId){
        $this->load->database('default', TRUE );
        $this->load->model('users_model');
        $user =  $this->users_model->getUserByEmailCompany($email,$companyId);
        if(!empty($user)){
            return $user['id'];
        }
        return false;
    }
    
    
    
    private function _explodeBookingTitlte($title){
        if(empty($title)){
            return false;
        }
        $tmp = explode('-',$title);
        if(isset($tmp[0])){
            $companyName = trim($tmp[0]);
            if(!empty($companyName)){
                return $companyName;
            }
        }
        return false;
    }
    
    function exportdb(){

        $coworkdb = $this->load->database('cowork', TRUE );
        $locations = $coworkdb->select('og.nid,og.og_description')
                ->from('og og')
                ->get()
                ->result_array();
        $lbmbookingsdb = $this->load->database('default', TRUE );
        $lbmbookingsdb->truncate('locations');
        foreach($locations as $k=>$l){
            $insertArr = array(
                'building_id'=> 1,
                'name'=>$l['og_description']
            );
            $lbmbookingsdb->insert('locations',$insertArr);
            $locations[$k]['dbid'] = $lbmbookingsdb->insert_id();
        }
        
        
        
        $coworkdb = $this->load->database('cowork', TRUE );
        $companies = $coworkdb->select('n.nid,n.title,nr.body,c.field_email_email,c.field_website_url,oa.group_nid')
                ->from('node n')
                ->join('node_revisions nr','nr.vid = n.vid','left')
                ->join('content_type_company c','c.nid = n.nid','left')
                ->join('og_ancestry oa','oa.nid = n.nid','left')
                ->where('n.type','company')                
                ->get()
                ->result_array();
        
        
        $lbmbookingsdb = $this->load->database('default', TRUE );
        $lbmbookingsdb->truncate('companies');
        foreach($companies as $k=>$c){
            foreach($locations as $l){
                if($c['group_nid']==$l['nid']){
                    $location_id = $l['dbid'];
                }
            }
            $insertArr = array(
                'building_id'=> 1,
                'location_id'=> $location_id,
                'name'=> $c['title'],
                'description'=>$c['body'],
                'email'=>$c['field_email_email'],
                'site_url'=>$c['field_website_url'],
            );
            $lbmbookingsdb->insert('companies',$insertArr);
            $companies[$k]['dbid'] = $lbmbookingsdb->insert_id();
        }
        
        $coworkdb = $this->load->database('cowork', TRUE );
        $users = $coworkdb->select('u.uid,u.name,u.mail')
                ->from('users u')
                ->get()
                ->result_array();
        $lbmbookingsdb = $this->load->database('default', TRUE );
        $lbmbookingsdb->truncate('users');
        $lbmbookingsdb->truncate('users_groups');
        foreach($users as $k=>$u){
            if(empty($u['mail']))
                continue;
            $tmp = explode(' ',$u['name']);
            $first_name = $tmp[0];
            unset($tmp[0]);
            $last_name = implode(' ',$tmp);
            
            $insertArr = array(
                'username'=> $u['name'],
                'email'=> $u['mail'],
                'password'=> '8fba0cf30b1796bc3b6a1fff8cd3e8855b7f9fd0',
                'active'=>1,
                'first_name'=>$first_name,
                'last_name'=>$last_name,
                'created_on'=>time(),
                'company_id'=>1
            );
            $lbmbookingsdb->insert('users',$insertArr);
            $users[$k]['dbid'] = $lbmbookingsdb->insert_id();
           
            $lbmbookingsdb->insert('users_groups',array('user_id'=> $users[$k]['dbid'],'group_id'=>2));
        }
        
        $coworkdb = $this->load->database('cowork', TRUE );
        $user_company = $coworkdb->select('c.nid,c.field_member_name_uid as uid')
                ->from('content_field_member_name c')
                ->join('users u','u.uid = c.field_member_name_uid','inner')
                ->get()
                ->result_array();
        
        $lbmbookingsdb = $this->load->database('default', TRUE );
        foreach($companies as $c){
            foreach($user_company as $uc){
                if($c['nid']==$uc['nid']){
                    foreach($users as $u){
                         if($uc['uid']==$u['uid']){
                             $updateArr = array(
                                 'company_id'=>$c['dbid'],
                             );
                             $lbmbookingsdb->where('id',$u['dbid']);
                             $lbmbookingsdb->update('users',$updateArr);
                         }
                    }
                }
            }
        }
        
        echo 'done';
    }
    function baseUrl()
    {
        echo base_url();
    }
    
    public function email_test()
    {
        $this->load->view('emails/booking_notification', array('room_name'=>'ROOM','from'=>'2013/07/08 14:00:00', 'to'=>'2013/07/08 15:00:00', 'username'=>'user', 'location_name'=>'LOCATION', 'booking_id'=>10, 'user_id'=>10));
    }
}