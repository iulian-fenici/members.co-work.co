<?php

class Maps_model extends MY_Model {

    public $_table = 'maps';

    public function __construct() {
        parent::__construct();
    }
    public function getMapDataByLocationId($locs_ids, $mapId = 0)
    {
        if ($mapId)
            $this->db->where('m.id', $mapId);
             $this->db->select('m.*, l.name loc_name, l.description loc_desc',false)
                        ->from('maps m')
                        ->join('locations l','l.id = m.location_id');
             
        if(is_array($locs_ids))
        {
            $this->db->where_in('m.location_id', $locs_ids);
            $res = $this->db->get()
                            ->result_array();
        }
        else
        {
            $this->db->where('m.location_id', $locs_ids);
                   $res = $this->db->get()
                            ->row_array();
        }
        return $res;
    }
    
    public function getMapDataById($mapId)
    {
        $res = $this->db->select()
                        ->from('maps m')                       
                        ->where('m.id', $mapId)
                        ->get()
                        ->row_array();
        return $res;
    }
    
    public function getMapsByIds($ids)
    {
        $res = $this->db->select()
                        ->from('maps m')                       
                        ->where_in('m.id', $ids)
                        ->order_by('order_by asc')
                        ->get()
                        ->result_array();
        return $res;
    }
    
    public function getLocationMaps($locationId)
    {
        $res = $this->db->select('m.*')
                        ->from('maps m')
                        ->where('m.location_id', $locationId)
                        ->order_by('m.order_by asc')
                        ->get()
                        ->result();
        foreach ($res as $location)
        {
            $result[] = $location;
        }
        return $result;
    }
    
    
}