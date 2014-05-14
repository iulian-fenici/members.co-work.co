<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Plans_model extends MY_Model {

    public $_table = 'rooms';

    public function __construct() {
        parent::__construct();
    }

    public function getMapDesksData($mapId)
    {
        $res = $this->db->select('d.*,cd.id as company_desk_id, cd.comment as desk_comment, cd.user_id, CONCAT(first_name, \' \', last_name) as username, c.name as company_name, c.id as company_id, c.email as company_email, c.phone as company_phone, c.site_url as company_site_url', false)
                        ->from('desks d')
                        ->join('company_desks cd', 'cd.desk_id = d.id', 'left')
                        ->join('companies c','c.id = cd.company_id','left')
                        ->join('users u', 'u.id = cd.user_id', 'left')
                        ->where('d.map_id', $mapId)
                        ->get()
                        ->result_array();
        return $res;
    }
    
    public function getMap($mapId)
    {
        $res = $this->db->select('d.*,cd.id as company_desk_id, cd.comment as desk_comment, c.name as company_name, c.id as company_id, c.email as company_email, c.phone as company_phone, c.site_url as company_site_url')
                        ->from('desks d')
                        ->join('company_desks cd', 'cd.desk_id = d.id', 'left')
                        ->join('companies c','c.id = cd.company_id','left')
                        ->where('d.map_id', $mapId)
                        ->get()
                        ->result_array();
        return $res;
    }
    public function getDeskDetails($companyDeskId)
    {
        $res = $this->db->select('d.*,cd.id as company_desk_id, cd.comment as desk_comment, c.description as company_description, c.name as company_name, c.id as company_id, c.email as company_email, c.phone as company_phone, c.site_url as company_site_url')
                        ->from('company_desks cd')
                        ->join('desks d', 'd.id = cd.desk_id', 'left')
                        ->join('companies c','c.id = cd.company_id','left')
                        ->where('cd.id', $companyDeskId)
                        ->get()
                        ->row_array();
        return $res;
    }
    public function getMerchantMap($mapId, $companyId)
    {
        $res = $this->db->select('d.*, cd.comment as desk_comment, c.name as company_name, c.id as company_id')
                        ->from('desks d')
                        ->join('company_desks cd', 'cd.desk_id = d.id', 'left')
                        ->join('companies c','c.id = cd.company_id','left')
                        ->where('d.map_id', $mapId)
                        ->where('c.id', $companyId)
                        ->or_where('c.id is null')
                        ->get()
                        ->result_array();
        //echo $this->db->last_query();
        return $res;
    }
    
    public function getDeskDataById($deskId)
    {
        $res = $this->db->select('d.*,m.location_id, cd.comment as desk_comment,  cd.user_id, CONCAT(first_name, \' \', last_name) as username, c.name as company_name, c.id as company_id', false)
                        ->from('desks d')
                        ->join('company_desks cd', 'cd.desk_id = d.id', 'left')
                        ->join('companies c','c.id = cd.company_id','left')
                        ->join('users u', 'u.id = cd.user_id', 'left')
                        ->join('maps m','m.id = d.map_id','left')    
                        ->where('d.id', $deskId)
                        ->get()
                        ->row_array();
        //echo $this->db->last_query();
        return $res;        
    }
    
    public function updateDeskAssignment($deskId, $deskData)
    {
        $companyDeskData = array(
            'company_id' => $deskData['company_id'],
            'desk_id' => $deskId,
            'user_id' => $deskData['user_id'],
            //'desk_number' => $deskData['desk_number'],
            'comment' => $deskData['desk_comment'],
        );
        $this->db->where('id', $deskId);
        $this->db->update('company_desks', $companyDeskData);
        
        return $this->db->affected_rows();
    }
    
    public function addDeskAssignment($deskData)
    {
        $companyDeskData = array(
            'company_id' => $deskData['company_id'],
            'desk_id' => $deskData['desk_id'],
            'user_id' => $deskData['user_id'],
            'map_id' => $deskData['map_id'],
            //'desk_number' => $deskData['desk_number'],
            'comment' => $deskData['desk_comment'],
        );
        $this->db->insert('company_desks', $companyDeskData);
        return $this->db->insert_id();
    }
    
    
    public function deleteDeskAssignment($deskId)
    {
        $this->db->where('desk_id', $deskId);
        $this->db->delete('company_desks');
        return $this->db->affected_rows();
    }
    
    public function updateDesk($deskId, $data)
    {
        $this->db->where('id', $deskId);
        $this->db->update('desks', $data);
        return $this->db->affected_rows();
    }
}
