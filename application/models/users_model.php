<?php
 
if(!defined('BASEPATH'))
    exit('No direct script access allowed');

class Users_model extends MY_Model {

    public $_table = 'users';

    public function __construct() {
        parent::__construct();
    }

    public function getUserList($companyId = null,$filters = null, $orderByArr = array('u.id' => 'ASC'), $limit = 0, $offset = 0) {
        if($companyId){
            $this->db->where('c.id', $companyId);
        }
        $this->apply_filters($filters, 'users_list');
        $this->apply_order($orderByArr, 'users_list');
        $res = $this->db->select('u.first_name,u.last_name,u.username, c.name as company_name,c.id as company_id, u.thumb_name, u.thumb_rel_path,
                                    CONCAT(u.first_name," ", u.last_name) as name, u.email, u.id,COUNT(b.id) as bookings_count,b.room_id as booking_room_id,b.location_id as booking_location_id, c.id as company_id ', false)
            ->from('users u')
            ->join('companies c', 'c.id = u.company_id', 'left')
            ->join('bookings b', 'b.user_id = u.id', 'left')
            ->join('users_groups ug', 'ug.user_id = u.id', 'inner')
            ->join('groups g', 'g.id = ug.group_id', 'inner')
            ->where('g.name = ', 'user')
            ->where('u.active', 1)
            ->group_by('u.id')
            ->get()
            ->result_array();
        //echo $this->db->last_query();

        return $res;
    }
    public function getCountAdmins($filters = null){
        return count($this->getAdminList($filters));
    }
    public function getAdminList($filters = null, $orderByArr = array('u.id' => 'ASC'), $limit = 0, $offset = 0) {

        $this->apply_filters($filters, 'admins_list');
        $this->apply_order($orderByArr, 'admins_list');
        $res = $this->db->select('u.first_name,u.last_name,u.username, u.thumb_name, u.thumb_rel_path,
                                    CONCAT(u.first_name," ", u.last_name) as name, u.email, u.id ', false)
            ->from('users u')
            ->join('users_groups ug', 'ug.user_id = u.id', 'inner')
            ->join('groups g', 'g.id = ug.group_id', 'inner')
            ->where('g.name = ', 'admin')
            ->where('u.active', 1)
            ->group_by('u.id')
            ->get()
            ->result_array();

        return $res;
    }

    public function getUserListDD($userId = null, $companyId = null, $first = 'Select User') {
        if($companyId){
            $this->db->where('c.id', $companyId);
        }
        if($userId){
            $this->db->where('u.id !=', $userId);
        }
        $users = $this->db->select('u.*, c.name as company_name', false)
            ->from('users u')
            ->join('companies c', 'c.id = u.company_id', 'left')
            ->join('users_groups ug', 'ug.user_id = u.id', 'inner')
            ->join('groups g', 'g.id = ug.group_id', 'inner')
            ->where('g.name = ', 'user')
            ->where('u.active', 1)
            //->group_by('d.id')
            ->get()
            ->result_array();

        $result = array('' => $first);
        foreach ($users as $user){
            $result[$user['id']] = $user['first_name'] . ' ' . $user['last_name'];
        }

        return $result;
    }

    public function deleteUser($userId) {
        $res2 = $this->delete($userId);
        return $res2;
    }

    public function getUserDataByUserId($userId) {
        //$res = $this->db->select('u.*, GROUP_CONCAT(ud.id) as departments')
        $res = $this->db->select('u.*')
            ->from('users u')
            ->where('u.id', $userId)
            ->where('u.active', 1)
            ->get()
            ->row_array();
        return isset($res) ? $res : false;
    }

    public function getCountUsers($filters = null, $orderByArr = array()) {
        $this->apply_filters($filters, 'users_list');
        $this->apply_order($orderByArr, 'users_list');
        $res = $this->db->select('COUNT(u.id)', false)
            ->from('users u')
            ->join('companies c', 'c.id = u.company_id', 'left')
            ->join('users_groups ug', 'ug.user_id = u.id', 'inner')
            ->join('groups g', 'g.id = ug.group_id', 'inner')
            ->where('g.name = ', 'user')
            ->where('u.active', 1);

        $res = $this->db->get()->row_array();

        return isset($res['count']) && !empty($res['count']) ? $res['count'] : false;
    }
    public function getUsersByIdsArr($idsArr){
        if(empty($idsArr)){
            return false; 
        }
        $this->db->select()
            ->from('users u')
            ->where_in('u.id', $idsArr)
            ->where('u.active', 1);
        $res = $this->db->get()->result_array();
        return $res;
    }
    
    public function deleteUserFromGroupByUserId($userId)
    {
        $this->db->where('user_id', $userId);
        $this->db->delete('users_groups');
        return $this->db->affected_rows();
    }
    
    public function getCompanyUsers($companyId){
        $res = $this->db->select()
            ->from('users u')
            ->where('u.company_id', $companyId)
            ->where('u.active', 1);

        $res = $this->db->get()->result_array();
        return $res;
    }
    
    public function getUserByEmailCompany($email, $companyId){
        $res = $this->db->select()
            ->from('users u')
            ->where('u.email', $email)
            ->where('u.company_id', $companyId)
            ->where('u.active', 1);
        $res = $this->db->get()->row_array();
        return $res;
    }
    
    
    public function getCompanyUsersEmailsArr($companyId){
        $res = $this->db->select('u.email')
            ->from('users u')
            ->where('u.company_id', $companyId)
            ->where('u.active', 1);
            
        $res = $this->db->get()->result_array();
        if(!empty($res)){
            $result = array();
            foreach($res as $v){
                $result[] = $v['email'];
            }
            $res = $result;
        }
        return $res;
    }
    public function getLocationUsersEmailsArr($locationId){
        $res = $this->db->select('u.email')
            ->from('users u')
            ->join('companies c','c.id=u.company_id','inner')
            ->join('company_location cl','cl.company_id = c.id','inner')
            ->join('locations l','l.id = cl.location_id','left')
            ->where('l.id', $locationId)
            ->where('u.active', 1);
        $res = $this->db->get()->result_array();
        if(!empty($res)){
            $result = array();
            foreach($res as $v){
                $result[] = $v['email'];
            }
            $res = $result;
        }
        return $res;
    }
    public function getUsersEmailsArr($idsArr){
        $res = $this->db->select('u.email')
            ->from('users u')
            ->where_in('u.id', $idsArr)
            ->where('u.active', 1);
            
        $res = $this->db->get()->result_array();
        if(!empty($res)){
            $result = array();
            foreach($res as $v){
                $result[] = $v['email'];
            }
            $res = $result;
        }
        return $res;
    }

    public function isFirstEntered($identity)
    {
        $res = $this->db->select('first_entered')
                        ->from('users')
                        ->where('email', $identity)
                        ->get()
                        ->row();

        return isset($res->first_entered) && $res->first_entered == 1?true: false;
    }
    
    public function resetFirstEntered($userId)
    {
        $data = array(
            'first_entered' => 0
        );
        $this->db->where('id', $userId);
        return $this->db->update('users', $data);
    }
    
}
