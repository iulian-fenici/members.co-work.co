<?php

class Announcements_model extends MY_Model {

    public $_table = 'announcements';

    public function __construct() {
        parent::__construct();
    }

    public function addAnnouncementsRef($dataArr) {
        $this->db->insert('announcements_ref', $dataArr);
        return $this->db->insert_id();
    }

    public function deleteAnnouncementsRef($announcementId, $table = null) {
        $this->db->where('announcement_id', $announcementId);
        if ($table)
            $this->db->where('table', $table);
        $this->db->delete('announcements_ref');
    }

    public function getAnnouncementsLocations($anouncementId) {
        $locations = $this->db->select('l.id, l.name')
                ->from('locations l')
                ->group_by('l.id')
                ->get()
                ->result_array();
        return $this->prepareAnnouncementsRecords($anouncementId,'locations',$locations);
    }

    public function getAnnouncementsCompanies($anouncementId,$locationIds) {
        $companies = $this->companies_model->getCompaniesListByLocationId($locationIds);
        return  $this->prepareAnnouncementsRecords($anouncementId,'companies',$companies);
    }

    public function getAnnouncementsUsers($anouncementId,$companyId = false) {
        
        if(!empty($companyId))
            $this->db->where('u.company_id',$companyId);
        $users = $this->db->select('u.id, u.username')
                ->from('users u')
                ->join('users_groups ug','ug.user_id = u.id','inner')
                ->join('groups g',"g.id = ug.group_id AND g.name = 'user' ",'inner')
                ->where('u.active', 1)
                ->get()
                ->result_array();
        return  $this->prepareAnnouncementsRecords($anouncementId,'users',$users);
    }
    public function getAnnouncementsArr($active = 1) {
        $nowDate = date('Y-m-d');
        $this->db->select('a.*,u.username, GROUP_CONCAT(ard.row_id) as locations_ids,GROUP_CONCAT(arc.row_id) as companies_ids,GROUP_CONCAT(art.row_id) as users_ids ')
                ->from('announcements a')
                ->join('users u','u.id = a.created_by','left')
                ->join('announcements_ref ard', 'ard.announcement_id = a.id AND ard.table = "locations"', 'left')
                ->join('announcements_ref arc', 'arc.announcement_id = a.id AND arc.table = "companies"', 'left')
                ->join('announcements_ref art', 'art.announcement_id = a.id AND art.table = "users"', 'left')
                ->where('DATE(date_from) <= "' . $nowDate . '" AND ( DATE(date_till) >= "' . $nowDate . '" OR DATE(date_till) = "0000-00-00" )')
                ->where('a.active', $active)
                ->order_by('a.date_from', 'DESC')
                ->group_by('a.id');
        $res = $this->db->get()->result_array();
        return $res;
    }
    public function getAnnouncementsArrByAnnouncementId($announcementId) {
        $this->db->select('u.username as user, a.*,GROUP_CONCAT(ard.row_id) as locations_ids,GROUP_CONCAT(arc.row_id) as companies_ids,GROUP_CONCAT(art.row_id) as users_ids ')
                ->from('announcements a')
                ->join('announcements_ref ard', 'ard.announcement_id = a.id AND ard.table = "locations"', 'left')
                ->join('announcements_ref arc', 'arc.announcement_id = a.id AND arc.table = "companies"', 'left')
                ->join('announcements_ref art', 'art.announcement_id = a.id AND art.table = "users"', 'left')
                ->join('users u', 'a.created_by = u.id', 'left')
                ->where('a.id = ', $announcementId);
        $res = $this->db->get()->row_array();
        return $res;
    }
    
    private function prepareAnnouncementsRecords($anouncementId,$table,$dataArr){
        $selected = array();
        $res = $this->db->select('ar.row_id')
                ->from('announcements_ref ar')
                ->join('announcements a', 'a.id = ar.announcement_id', 'left')
                ->where('ar.table', $table)
                ->where('a.id', $anouncementId)
                ->get()
                ->result_array();
        foreach ($res as $k => $v) {
            $selected[] = $v['row_id'];
        }
        
        foreach ($dataArr as $k => $v) {
            if (in_array($v['id'], $selected)) {
                $dataArr[$k]['checked'] = true;
            }
            else
                $dataArr[$k]['checked'] = false;
        }
        return $dataArr;
    }
    public function getCountAnnouncements($filters = null, $orderByArr = array(), $limit = 0, $offset = 0,$admin = 1,$userId = false,$userLocationsIds = false) {
        return count($this->getAnnouncementsList($filters, $orderByArr, $limit, $offset,$admin,$userId));
    }
    public function getAnnouncementsList($filters = null, $orderByArr = array('date_from' => 'DESC'), $limit = 0, $offset = 0,$admin = 1,$userId = false,$userLocationsIds = false){
   
        $this->apply_filters($filters, 'announcements_list');
        
        $this->apply_order($orderByArr, 'announcements_list', array('date_from' => 'DESC'));
        
        if(!empty($userId)){
            $this->db->where('a.created_by',$userId);
        }
        
        if(!empty($userLocationsIds)){
            $this->db->join('announcements_ref ard', 'ard.announcement_id = a.id AND ard.table = "locations"', 'left');
            $this->db->where_in('ard.row_id',$userLocationsIds);
        }
        
        $res = $this->db->select('a.*, count(c.id) as comments_count, u.username')
                    ->from('announcements a')
                    ->join('comments c','c.announcement_id=a.id','left')
                    ->join('users u','u.id=a.created_by','left')                    
                    ->group_by('a.id');   
        $res = $this->db->get()->result_array();
        return $res;
    }
    
    public function deleteFroAnnouncementsyUserId($userId)
    {
        $idsArr = $this->getAnnouncementsIdsArrByUserId($userId);
        if (count($idsArr))
            return $this->announcenments_model->deleteAnnouncementsByIdsArr($idsArr);
        else {
            return true;
        }
    }
    
    public function deleteAnnouncementsRefByUserId($userId)
    {
        $this->db->where('table', 'users');
        $this->db->where('row_id', $userId);
        $this->db->delete('announcements_ref');
        return $this->db->affected_rows();
        
    }
    
    public function getNotificationsAnnouncementsArr($active = 1) {
        $nowDate = date('Y-m-d');
        $this->db->select('a.*,u.username,u.id as user_id, c.name as company_name, GROUP_CONCAT(ard.row_id) as locations_ids,GROUP_CONCAT(arc.row_id) as companies_ids,GROUP_CONCAT(art.row_id) as users_ids ')
                ->from('announcements a')
                ->join('users u','u.id = a.created_by','left')
                ->join('companies c','c.id=u.company_id','left')
                ->join('announcements_ref ard', 'ard.announcement_id = a.id AND ard.table = "locations"', 'left')
                ->join('announcements_ref arc', 'arc.announcement_id = a.id AND arc.table = "companies"', 'left')
                ->join('announcements_ref art', 'art.announcement_id = a.id AND art.table = "users"', 'left')
                ->where('DATE(date_from) <= "' . $nowDate . '" AND ( DATE(date_till) >= "' . $nowDate . '" OR DATE(date_till) = "0000-00-00" )')
                ->where('a.active', $active)
                ->where('a.send_email', 1)
                ->group_by('a.id');
        $res = $this->db->get()->result_array();
        return $res;
    }

}
