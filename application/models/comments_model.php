<?php
class Comments_model extends MY_Model {
    
    public function __construct() {
        parent::__construct();
    }
    
    public function getComents($filter=false)
    {
        if ($filter)
        {
            foreach ($filter as $key=>$val)
            {
                $this->db->where($key,$val);
            }
        }
        return $this->db->select('c.*, u.username')
                ->from('comments c')
                ->join('users u','u.id=user_id')
                ->get()
                ->result();
    }
    
    public function addComent($data=null)
    {
        if ($data)
        {
            $this->db->set($data)
                    ->insert('comments');
        }
    }
    
    public function updateComment($data=false, $id=false)
    {
        if ($data&&$id)
        {
            $this->db->where('id',$id);
            $this->db->set($data)
                    ->update('comments');
        }
    }
    
    public function dellComment($id=null)
    {
        if ($id)
        {
            $this->db->where('id',$id)
                    ->delete('comments');
        }
    }
}
