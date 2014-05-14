<?php

class Companies_model extends MY_Model {

    public $_table = 'companies';

    public function __construct() {
        parent::__construct();
    }
    
    public function getCompaniesById($companyId){
        $res = $this->db->select('c.*, GROUP_CONCAT(DISTINCT l.id) as locs_ids, GROUP_CONCAT(DISTINCT l.name) as locs_names, COUNT(DISTINCT u.id) as users_count')
                    ->from('companies c')
                    ->join('company_location cl','cl.company_id = c.id','inner')
                    ->join('locations l','l.id = cl.location_id','left')
                    ->join('users u', 'u.company_id = c.id', 'left')
                    ->where('c.id',$companyId)
                    ->where('c.deleted',0);
        return $res->get()->row_array();
    }
    
    public function getCompaniesList($filters = null, $orderByArr = array('c.id' => 'ASC'), $limit = 0, $offset = 0){
        $this->apply_filters($filters, 'companies_list');
        $this->apply_order($orderByArr, 'companies_list');
        $res = $this->db->select('c.*, GROUP_CONCAT(DISTINCT l.id) as locs_ids, GROUP_CONCAT(DISTINCT l.name) as locs_names, COUNT(DISTINCT u.id) as users_count')
                    ->from('companies c')
                    ->join('company_location cl','cl.company_id = c.id','inner')
                    ->join('locations l','l.id = cl.location_id','left')
                    ->join('users u', 'u.company_id = c.id','left')
                    ->where('c.deleted',0)
                    ->group_by('c.id')        
                    ->get()->result_array();
      //  debug($res);die;
        return $res;
        
    }
    
    public function getCompaniesListByLocationId($user_locs=false){
        if(!empty($user_locs) && is_array($user_locs))
        {
            $this->db->where_in('l.id',$user_locs);
        }
        $res = $this->db->select('c.*, GROUP_CONCAT(DISTINCT l.id) as locs_ids, GROUP_CONCAT(DISTINCT l.name) as locs_names, COUNT(DISTINCT u.id) as users_count')
                    ->from('companies c')
                    ->join('company_location cl','cl.company_id = c.id','inner')
                    ->join('locations l','l.id = cl.location_id','left')
                    ->join('users u', 'u.company_id = c.id', 'left')
                    ->where('c.deleted',0)                 
                    ->group_by('c.id')
                    ->order_by('c.name');        
        return $res->get()->result_array();
    }
    
    public function getCompaniesListDD($locationId = null)
    {
        if($locationId){
            $this->db->where_in('l.id',$locationId);
        }
        $companies = $this->db->select('c.id, c.name')
                                ->from('companies c')
                                ->join('company_location cl','cl.company_id = c.id','inner')
                                ->join('locations l','l.id = cl.location_id','left')
                                ->where('c.deleted',0)
                                ->get()
                                ->result_array();

        $result = array('' => 'Select Company');
        foreach ($companies as $company)
        {
            $result[$company['id']] = $company['name'];
        }

        return $result;
    }
    public function getCountCompanies($filters = null, $orderByArr = array()) {
        $this->apply_filters($filters, 'companies_list');
        $this->apply_order($orderByArr, 'companies_list');
        $res = $this->db->select('COUNT(c.id)')
                    ->from('companies c')
                    ->join('company_location cl','cl.company_id = c.id','inner')
                    ->join('locations l','l.id = cl.location_id','left')
                    ->where('c.deleted',0)
                    ->group_by('c.id');   
        $res = $this->db->get()->row_array();

        return isset($res['count']) && !empty($res['count']) ? $res['count'] : false;
    }
    
    public function getCompanyNameById($companyId)
    {
        $data = $this->db->select('c.name')
                                ->from('companies c')
                                ->where('id', $companyId)
                                ->where('c.deleted',0)
                                ->get()
                                ->row_array();
        return isset($data['name']) && $data['name']? $data['name']:false;
    }
    
    public function getCompanyIdByName($companyName)
    {
        $data = $this->db->select('c.id')
                                ->from('companies c')
                                ->where('name', $companyName)
                                ->where('c.deleted',0)
                                ->get()
                                ->row_array();
        return isset($data['id']) && $data['id']? $data['id']:false;
    }
    
    
    public function addCompaniesRef($data){
        if(empty($data)){
            return false;
        }
        
        $this->db->insert_batch('company_location', $data); 
        return true;
    }
    public function deleteCompaniesRef($companyId){
        settype($companyId,'integer');
        if(empty($companyId)){
            return false;
        }
        
        $this->db->where('company_id', $companyId);
        $this->db->delete('company_location');
        return true;
    }
    public function getCompaniesRef($companyId){
        settype($companyId,'integer');
        if(empty($companyId)){
            return false;
        }
        
        $this->db->where('company_id', $companyId);
        $data = $this->db->select('c.name')
                    ->from('company_location cl')
                    ->where('id', $companyId)
                    ->where('c.deleted',0)
                    ->get()
                    ->row_array();
        return true;
    }
    public function getCompanyLocations($companyId){
        $locations = $this->db->select('l.id, l.name')
                ->from('locations l')
                ->group_by('l.id')
                ->get()
                ->result_array();
        return $this->prepareAnnouncementsRecords($companyId,$locations);
    }
    
    private function prepareAnnouncementsRecords($companyId,$dataArr){
        $selected = array();
        $res = $this->db->select('cl.location_id')
                ->from('company_location cl')
                ->where('cl.company_id', $companyId)
                ->get()
                ->result_array();
        foreach ($res as $k => $v) {
            $selected[] = $v['location_id']; 
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
}
