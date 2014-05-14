<?php

class Locations_model extends MY_Model {

    public $_table = 'locations';

    public function __construct() {
        parent::__construct();
    }
    
    public function getPlansLocationsList(){

        $res = $this->db->select('l.*,GROUP_CONCAT(m.id) as map_ids')
                    ->from('locations l')    
                    ->join('maps m', 'm.location_id = l.id', 'left')
                    ->group_by('l.id'); 
        $res = $res->get()->result_array();
        foreach ($res as $k => $location)
        {
            $res[$k]['maps'] = $this->maps_model->getMapsByIds(explode(',',$location['map_ids']));
        }
        return $res;
    }
    
    public function getLocationsList($filters = null, $orderByArr = array('l.id' => 'ASC'), $limit = 0, $offset = 0){
        $this->apply_filters($filters, 'locations_list');
        $this->apply_order($orderByArr, 'locations_list');
        $res = $this->db->select('l.*,COUNT(c.id) as companies_count')
                    ->from('locations l')  
                    ->join('company_location cl', 'cl.location_id = l.id', 'left')
                    ->join('companies c', 'c.id = cl.company_id', 'left')
                    ->group_by('l.id'); 
        $res = $res->get()->result_array();
        if(isset($res)&&!empty($res)){
            foreach($res as $k=>$v){
                $resRooms = $this->db->select('COUNT(r.id) as rooms_count')
                    ->from('rooms r')
                    ->where('r.location_id',$v['id'])
                    ->get()
                    ->row_array();
                $res[$k]['rooms_count']=$resRooms['rooms_count'];
            }
            
        }

        return $res;
    }
    
    public function getLocationsListDD()
    {
        $locations = $this->db->select('id, name')
                                ->from('locations')
                                ->get()
                                ->result_array();

        $result = array('' => 'Select Location');
        foreach ($locations as $location)
        {
            $result[$location['id']] = $location['name'];
        }

        return $result;
    }
    public function getCountLocations($filters = null, $orderByArr = array()) {
        $this->apply_filters($filters, 'locations_list');
        $this->apply_order($orderByArr, 'locations_list');
        $res = $this->db->select('COUNT(l.id)')
                    ->from('locations l')
                    ->group_by('l.id');
        $res = $this->db->get()->row_array();

        return isset($res['count']) && !empty($res['count']) ? $res['count'] : false;
    }
    
    public function getLocationsByCompanyId($companyId)
    {
        $this->load->model('maps_model');
        $res = $this->db->select('l.*')
                ->from('company_location cl')
                ->join('locations l','l.id = cl.location_id')
                ->where('cl.company_id',$companyId)
                ->get()
                ->result();
//        foreach ($res as $k => $location)
//        {
//            $res[$k]->map_ids = $this->maps_model->getLocationMaps($location->id);
//        }
      
      return $res;
    }
    
    public function getLocationIdByName($locationName)
    {
        $data = $this->db->select('l.id')
                                ->from('locations l')
                                ->where('name', $locationName)
                                ->get()
                                ->row_array();
        return isset($data['id']) && $data['id']? $data['id']:false;
    }
    
    
    public function getLocationsVariableByCompanyId($companyId, $variable) {
        $locations = $this->getLocationsByCompanyId($companyId);
        $resultArr = array();
        foreach ($locations as $v) {
            array_push($resultArr, $v->{$variable});
        }
        return $resultArr;
    }
}