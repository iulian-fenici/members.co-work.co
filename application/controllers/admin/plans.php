<?php

if(!defined('BASEPATH'))
    exit('No direct script access allowed');

//Admin panel 
class Plans extends My_Controller {

    public function __construct() {
        parent::__construct();
        $this->checkAdminLoggedIn();
        $this->load->model(array('users_model','plans_model',  'companies_model','locations_model','maps_model'));
        $this->load->library(array('form_validation', 'ion_auth'));
        $this->load->helper(array('dates_helper','send_message_helper'));
    }
    public function locations()
    {
        $this->data['locationsArr'] = $this->locations_model->getPlansLocationsList();
   
        $this->viewData['header'] = $this->load->view('admin/main/header', $this->data, true);
        $this->viewData['menu'] = $this->load->view('admin/main/menu', $this->data, true);
        $this->viewData['sidebar'] = $this->load->view('admin/main/sidebar', $this->data, true);
        $this->viewData['message'] = $this->load->view('admin/main/message', $this->data, true);
        $this->viewData['content'] = $this->load->view('admin/plans/locations_list', $this->data, true);
        $this->viewData['footer'] = $this->load->view('admin/main/footer', $this->data, true);
        $this->load->view('admin/main/content', $this->viewData);
    }
    
    public function assign_desk($locationId = 3, $mapId = 0)
    {
        settype($locationId, 'integer');
        settype($mapId, 'integer');
        $this->data = array();
        $mapData = $this->maps_model->getMapDataByLocationId($locationId, $mapId);

        if(empty($mapData)){
            $this->messages->add("This location has no map", "error");
            redirect('/admin/dashboard/index');
        }
        $desksData = $this->plans_model->getMapDesksData($mapData['id']);

        $this->data['map'] = file_exists(FCPATH.$mapData['source'])?$mapData['source']:'';
        $this->data['mapId'] = $mapData['id'];
        $this->data['imageMap'] = $this->buildImageMap($desksData);

        $this->viewData['header'] = $this->load->view('admin/main/header', $this->data,true);
        $this->viewData['menu'] = $this->load->view('admin/main/menu', $this->data,true);
        $this->viewData['sidebar'] = $this->load->view('admin/main/sidebar', $this->data,true);
        $this->viewData['message'] = $this->load->view('admin/main/message', $this->data,true);
        $this->viewData['content'] = $this->load->view('admin/plans/plan_map', $this->data,true);
        $this->viewData['footer'] = $this->load->view('admin/main/footer', $this->data,true);
        $this->load->view('admin/main/content', $this->viewData);
    }
    
    private function buildImageMap($data)
    {
        $areaHtml = '';
        foreach ($data as $desk)
        {
            $areaHtml .= '<area shape="rect" 
                                id="'.(isset($desk['id'])?$desk['id']:'').'" 
                                class="desk" 
                                company="'.(isset($desk['company_name'])?$desk['company_name']:'').'" 
                                email="'.(isset($desk['email'])?$desk['company_email']:'').'" 
                                phone="'.(isset($desk['company_phone'])?$desk['company_phone']:'').'" 
                                site_url="'.(isset($desk['company_site_url'])?$desk['company_site_url']:'').'" 
                                company_id="'.(isset($desk['company_id'])?$desk['company_id']:'').'" 
                                username="'.(isset($desk['username'])?$desk['username']:'').'" 
                                desk_number="'.(isset($desk['desk_number'])?$desk['desk_number']:'').'" 
                                desk_comment="'.(isset($desk['desk_comment'])?$desk['desk_comment']:'').'" 
                                coords="'.(isset($desk['x1'])?$desk['x1']:'').','.(isset($desk['y1'])?$desk['y1']:'').','.(isset($desk['x2'])?$desk['x2']:'').','.(isset($desk['y2'])?$desk['y2']:'').'" 
                                href="#" 
                                alt="'.(isset($desk['company_name'])?$desk['company_name']:'').' Desk: '.(isset($desk['id'])?$desk['id']:'').'"
                                >';
        }
        return $areaHtml;
    }
    
    public function editDeskAssignment($deskId)
    {
        $deskId = (int) $deskId;
        $this->data['deskData'] = $this->plans_model->getDeskDataById($deskId);

        $this->returnData = array();
        if($this->input->post('save')){
            $this->_getDataFromDeskForm();
            $fullValidation = true; // Validate all fields
            if (empty($this->data['deskData']['desk_comment']) &&
                empty($this->data['deskData']['user_id']) &&
                empty($this->data['deskData']['company_id']) &&
                !empty($this->data['deskData']['desk_number'])
                )
            {
                $fullValidation = false; // Validate the desk_number field only if other fields are empty
            }
            $this->_setDeskAssignmentValidationRules($fullValidation);
            
            if($this->form_validation->run() == true)
            {
                
                $userData = $this->users_model->getUserDataByUserId($this->data['deskData']['user_id']);

                if($this->_saveDeskData(1, $deskId, $fullValidation)){
                    $this->returnData = array(
                        'success' => 1,
                        'desk_id' => $deskId,
                        'company_id' => $this->data['deskData']['company_id'],
                        'user_id' => $this->data['deskData']['user_id'],
                        'username' => (isset($userData['first_name'])?$userData['first_name']:''). ' '. (isset($userData['last_name'])?$userData['last_name']:''),
                        'company' => $this->data['deskData']['company_name'],
                        'desk_number' => $this->data['deskData']['desk_number'],
                        'desk_comment' => $this->data['deskData']['desk_comment'],
                    );
                    //var_dump($this->returnData );
                }
               
            }
            else {
                $this->returnData = array(
                    'error' => 1,
                    'text' => validation_errors()
                );
            }
            $this->load->view('merchant/echodata', array('data' => json_encode($this->returnData)));
            return;
        }
        $companiesList = $this->companies_model->getCompaniesListDD($this->data['deskData']['location_id']);
        $this->data['companiesList'] = form_dropdown('company_id', $companiesList, set_value('company_id',isset($this->data['deskData']['company_id'])&&!empty($this->data['deskData']['company_id']) ? $this->data['deskData']['company_id']:0), 'id="company_id"');
        $usersList = $this->users_model->getUserListDD(null, $this->data['deskData']['company_id']);
        $this->data['usersList'] = form_dropdown('user_id', $usersList, set_value('user_id',isset($this->data['deskData']['user_id'])&&!empty($this->data['deskData']['user_id']) ? $this->data['deskData']['user_id']:0), 'id="user_id"');
//        var_dump($this->data['deskData']);die;
        $this->load->view('admin/plans/edit_desk_assignment',$this->data);
    }
    
     private function _setDeskAssignmentValidationRules($fullValidation) {
         if ($fullValidation)
         {
            $this->form_validation->set_rules('company_id', 'Company Id', 'xss_clean|required|trim');
            $this->form_validation->set_rules('user_id', 'User Id', 'xss_clean|trim');
            $this->form_validation->set_rules('desk_comment', 'Desk Comment', 'xss_clean|trim');
         }
         $this->form_validation->set_rules('desk_number', 'Desk Number', 'xss_clean|trim');
    }
    
    public function _saveDeskData($mode, $deskId, $fullValidation = 1)
    {

        settype($deskId, 'integer');
        if ($mode)
        {
            if (!empty($this->data['deskData']['company_id']))
                $this->plans_model->updateDeskAssignment($deskId, $this->data['deskData']);
            if ($this->data['deskData']['desk_number'])
                $res = $this->plans_model->updateDesk($deskId, array('desk_number' => $this->data['deskData']['desk_number']));
                return true;
        }
        else {
            if (!empty($this->data['deskData']['company_id']))
                $this->plans_model->addDeskAssignment($this->data['deskData']);
            if ($this->data['deskData']['desk_number'])
            {
                    $res = $this->plans_model->updateDesk($deskId, array('desk_number' => $this->data['deskData']['desk_number']));
            }
            return true;
        }
    }
    
    public function addDeskAssignment($deskId)
    {

        $deskId = (int) $deskId;
        $this->data['deskData'] = $this->plans_model->getDeskDataById($deskId);
       
        $this->data['deskData']['desk_id'] = $deskId;
        $this->returnData = array();
        if($this->input->post('save')){
            $this->_getDataFromDeskForm();
            $fullValidation = true; // Validate all fields
            if (empty($this->data['deskData']['desk_comment']) &&
                empty($this->data['deskData']['user_id']) &&
                empty($this->data['deskData']['company_id']) &&
                !empty($this->data['deskData']['desk_number'])
                )
            {
                $fullValidation = false; // Validate the desk_number field only if other fields are empty
            }
            $this->_setDeskAssignmentValidationRules($fullValidation);

            if($this->form_validation->run() == true)
            {
                $userData = $this->users_model->getUserDataByUserId($this->data['deskData']['user_id']);
                $newId = $this->_saveDeskData(0, $deskId, $fullValidation);
                if($newId){
                    $this->returnData = array(
                        'success' => 1,
                        'desk_id' => $deskId,
                        'company_id' => $this->data['deskData']['company_id'],
                        'user_id' => $this->data['deskData']['user_id'],
                        'company' => $this->data['deskData']['company_name'],
                        'desk_number' => $this->data['deskData']['desk_number'],
                        'desk_comment' => $this->data['deskData']['desk_comment'],
                    );
                }
               
            }
            else {
                $this->returnData = array(
                    'error' => 1,
                    'text' => validation_errors()
                );
            }
            $this->load->view('merchant/echodata', array('data' => json_encode($this->returnData)));
            return;
        }
        $companiesList = $this->companies_model->getCompaniesListDD($this->data['deskData']['location_id']);
        $this->data['companiesList'] = form_dropdown('company_id', $companiesList, set_value('company_id',isset($this->data['deskData']['company_id'])&&!empty($this->data['deskData']['company_id']) ? $this->data['deskData']['company_id']:0), 'id="company_id"');
//        var_dump($this->data['deskData']);die;
        $this->load->view('admin/plans/add_desk_assignment',$this->data);
    }
    
     private function _getDataFromDeskForm() {
        $this->data['deskData']['company_id'] = $this->input->post('company_id', true);
        $this->data['deskData']['user_id'] = $this->input->post('user_id', true);
        $this->data['deskData']['company_name'] = $this->companies_model->getCompanyNameById($this->data['deskData']['company_id']);
        $this->data['deskData']['desk_number'] = $this->input->post('desk_number', true);
        $this->data['deskData']['desk_comment'] = $this->input->post('desk_comment', true);
    }
    
    public function deleteDeskAssignment($deskId)
    {
        settype($deskId, 'integer');
        $res = $this->plans_model->deleteDeskAssignment($deskId);
        if ($res)
        {
            $this->returnData = array(
                'success' => 1,
                'desk_id' => $deskId,
                'message' => 'Desk assignment deleted successfully'
            );   
        }
        else{
                $this->returnData = array(
                    'error' => 1,
                    'message' => 'Error on delete desk assignment'
                );
        }
        $this->load->view('merchant/echodata', array('data' => json_encode($this->returnData)));
        return;
    }
    
    public function show_desks_print($mapId = false)
    {
        settype($mapId, 'integer');
        $this->data = array();
        $mapData = $this->maps_model->getMapDataById($mapId);
        if(empty($mapData)){
            $this->messages->add("This location has no map", "error");
            redirect('/admin/dashboard/index');
        }
        $this->data['map'] = $mapData['source'];
        $this->data['mapId'] = $mapData['id'];
        $this->data['locationId'] = $mapData['location_id'];
        $mapData = $this->plans_model->getMapDesksData($mapId);

        $this->data['imageMap'] = $this->buildImageMap($mapData);
        $this->data['print_media'] = 1;
        $this->data['print_button'] = '
                    <a href="#" class="print-button" onclick="window.print();"><i class="icon icon-print"></i> Print this page</a><br>
                    <a href="/admin/plans/assign_desk/'.$this->data['locationId'].'"><i class="icon icon-back"></i> Go Back</a> ';
        $this->viewData['header'] = $this->load->view('merchant/main/print_header', $this->data,true);
        $this->viewData['content'] = $this->load->view('admin/plans/plan_map_print', $this->data,true);
        $this->load->view('merchant/main/content', $this->viewData);
    }   
}