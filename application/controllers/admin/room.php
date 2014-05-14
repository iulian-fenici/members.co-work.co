<?php

if(!defined('BASEPATH'))
    exit('No direct script access allowed');

class Room extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->checkAdminLoggedIn();
        $this->load->model(array('rooms_model','companies_model','locations_model'));
        $this->load->library(array('form_validation', 'ion_auth'));
        $this->load->helper(array('upload_helper'));
        
    }
    
    public function rooms_list()
    {
        $this->load->library('pagination');
        $page = (int) $this->input->get('page');
        $this->load->config('pagination', true);       
        $getArr = $this->input->get();
        $configPagination = $this->config->item('pagination_default', 'pagination');
        
        $this->data['rooms'] = $this->rooms_model->getRoomsList($getArr, array($this->input->get('order', false) => $this->input->get('direction', false)), $configPagination['per_page'], $page);
        
        $this->load->config('pagination', true);

        $configPagination['base_url'] = $this->utility->build_filter_href('/admin/room/rooms_list', array('page' => null));
        $configPagination['total_rows'] = $this->rooms_model->getCountRooms($getArr);
        //var_dump($configPagination['total_rows']);die();
        $this->pagination->initialize($configPagination);
        $this->data['pagination'] = $this->pagination->create_links();
        
        $this->viewData['header'] = $this->load->view('admin/main/header', $this->data, true);
        $this->viewData['menu'] = $this->load->view('admin/main/menu', $this->data, true);
        $this->viewData['sidebar'] = $this->load->view('admin/main/sidebar', $this->data, true);
        $this->viewData['message'] = $this->load->view('admin/main/message', $this->data, true);
        $this->viewData['content'] = $this->load->view('admin/rooms/rooms_list', $this->data, true);
        $this->viewData['footer'] = $this->load->view('admin/main/footer', $this->data, true);
        $this->load->view('admin/main/content', $this->viewData);
    }
    
    public function edit_room($roomId = 0)
    {
        $this->data = array();
        
        if ($roomId)
        {
            $this->data['roomData'] = (array)$this->rooms_model->get($roomId);
            if(empty($this->data['roomData'])){
                $this->messages->add("Room data not found", "error");
                redirect('/admin/room/rooms_list');
            }
        }

        $this->_setValidationRules();

        if($this->input->post('save')){
            
            if($this->form_validation->run() == true){
                $this->_getDataFromForm();   
               
                if($this->_saveData($roomId))
                    $this->messages->add("Room edited successfully", "success");
                redirect('/admin/room/rooms_list');
            }
            else {
                //validation errors
            }
        }
        $locationsList = $this->locations_model->getLocationsListDD();
        
        $this->data['id'] = isset($this->data['roomData']) && !empty($this->data['roomData'])? $this->data['roomData']['id']:$roomId;
        $this->data['locationsList'] = form_dropdown('location_id', $locationsList, set_value('location_id',isset($this->data['roomData']['location_id'])&&!empty($this->data['roomData']['location_id']) ? $this->data['roomData']['location_id']:0));
        //view merchant
        $this->viewData['header'] = $this->load->view('admin/main/header', $this->data, true);
        $this->viewData['menu'] = $this->load->view('admin/main/menu', $this->data, true);
        $this->viewData['sidebar'] = $this->load->view('admin/main/sidebar', $this->data, true);
        $this->viewData['message'] = $this->load->view('admin/main/message', $this->data, true);
        $this->viewData['content'] = $this->load->view('admin/rooms/edit_room', $this->data, true);
        $this->viewData['footer'] = $this->load->view('admin/main/footer', $this->data, true);
        $this->load->view('admin/main/content', $this->viewData);
    }
    
    public function delete_room($roomId)
    {
        $this->data = array();
        if ($roomId)
        {
            $this->data['roomData'] = (array)$this->rooms_model->get($roomId);
            if(empty($this->data['roomData'])){
                $this->messages->add("Room data not found", "error");
                redirect('/admin/room/rooms_list');
            }
            
            $res = $this->rooms_model->delete($roomId);
            if ($res)
            {
                $this->messages->add("Room deleted successfully", "success");
            }
            else
                $this->messages->add("Error on delete room", "error");
            redirect('/admin/room/rooms_list');
        }

    }
 
    private function _setValidationRules() {
        //Personal data
        $this->form_validation->set_rules('name', 'Company name', 'required|xss_clean|trim');
        $this->form_validation->set_rules('description', 'Company description', 'xss_clean|trim');
        $this->form_validation->set_rules('location_id', 'Location', 'xss_clean|trim|required');
      }
      
    private function _getDataFromForm() {
        $this->data['name'] = $this->input->post('name', true);
        $this->data['description'] = $this->input->post('description', true);
        $this->data['uploaded-photo'] = $this->input->post('uploaded-photo', true);
        $this->data['location_id'] = $this->input->post('location_id', true);
    }

    private function _saveData($roomId = 0) {
        
        $updateRoomData = array(
            'name' => $this->data['name'],
            'building_id' => $this->buildingId,
            'description' => $this->data['description'],
            'location_id' => $this->data['location_id'],
        );
        
        if(isset($this->data['uploaded-photo']) && !empty($this->data['uploaded-photo'])){
            $result = prepareImages($this->data['uploaded-photo'], 'room');
            $updateRoomData['thumb_abs_path'] = $result['new_abs_thumb_path'];
            $updateRoomData['thumb_rel_path'] = $result['new_rel_thumb_path'];
            $updateRoomData['thumb_name'] = $result['file_name'];
            $updateRoomData['big_abs_path'] = $result['new_abs_path'];
            $updateRoomData['big_rel_path'] = $result['new_rel_path'];
            $updateRoomData['big_name'] = $result['file_name'];
        }
        
        if ($roomId)
            $resultUpdate = $this->rooms_model->update($roomId, $updateRoomData);
        else {
            $resultUpdate = $this->rooms_model->insert($updateRoomData);
        }
        return $resultUpdate;
    }
    public function delete_room_photo(){
        
        if($this->input->post('roomId')){
            $roomId = $this->input->post('roomId');
            $this->data = array();
            $this->data['roomData'] = $this->rooms_model->get($roomId);

            if(!empty($this->data['roomData'])){
                //unlink files
                if(!empty($this->data['roomData']->thumb_abs_path) && !empty($this->data['roomData']->thumb_name) && file_exists($this->data['roomData']->thumb_abs_path.$this->data['roomData']->thumb_name)){
                    unlink($this->data['roomData']->thumb_abs_path.$this->data['roomData']->thumb_name);
                }
                if(!empty($this->data['roomData']->big_abs_path) && !empty($this->data['roomData']->big_name) && file_exists($this->data['roomData']->big_abs_path.$this->data['roomData']->big_name)){
                    unlink($this->data['roomData']->big_abs_path.$this->data['roomData']->big_name);
                }
                //remove from db
                $updateRoomData['thumb_abs_path'] = '';
                $updateRoomData['thumb_rel_path'] = '';
                $updateRoomData['thumb_name'] = '';
                $updateRoomData['big_abs_path'] = '';
                $updateRoomData['big_rel_path'] = '';
                $updateRoomData['big_name'] = '';
                $this->rooms_model->update($this->data['roomData']->id, $updateRoomData);
                
                $this->load->view('admin/echodata',array('data'=>array('success'=>'Photo deleted successfully')));
            }else{
                $this->load->view('admin/echodata',array('data'=>array('error'=>'Photo deleted unsuccessfully')));
            }
        }
    }   
}