<?php

if(!defined('BASEPATH'))
    exit('No direct script access allowed');

//Admin panel 
class Plans extends My_Controller {

    public function __construct() {
        parent::__construct();
        $this->checkLoggedIn();
        $this->load->model(array('plans_model',  'companies_model','users_model','maps_model'));
        $this->load->library(array('form_validation', 'ion_auth'));
        $this->load->helper(array('dates_helper','send_message_helper'));
    }
    
    public function show_desks()
    {
        $this->data = array();
        $this->load->model('locations_model');
        $this->load->model('maps_model');
        $user_locs = $this->locations_model->getLocationsByCompanyId($this->session->userdata('company_id'));

        $locs_ids = array();
        foreach ($user_locs as $key=>$val)
        {
            $locs_ids[]= $val->id;
            $user_locs[$key]->maps = $this->maps_model->getLocationMaps($val->id);
        }        
    
//        $this->data['maps'] = $this->maps_model->getMapDataByLocationId($locs_ids);    
        //$this->data['maps'] = $this->maps_model->getMapsByIds(explode(',',$user_locs->map_ids));
        $this->data['locationsArr'] = $user_locs;
        $this->viewData['header'] = $this->load->view('merchant/main/header', $this->data,true);
        $this->viewData['menu'] = $this->load->view('merchant/main/menu', $this->data,true);        
        $this->viewData['sidebar'] = $this->load->view('merchant/main/sidebar', $this->data,true);
        $this->viewData['message'] = $this->load->view('merchant/main/message', $this->data,true);
        $this->viewData['content'] = $this->load->view('merchant/plans/plans_list', $this->data,true);
        $this->viewData['footer'] = $this->load->view('merchant/main/footer', $this->data,true);
        $this->load->view('admin/main/content', $this->viewData);
    }
    
    public function show_map($mapId)
    {                 
        $mapData = $this->maps_model->getMapDataById($mapId);
        if (!empty($mapData))
        {
            $this->data['map'] = $mapData['source'];
            $this->data['mapId'] = $mapData['id'];
            $desksData = $this->plans_model->getMapDesksData($mapData['id']);
            $this->data['imageMap'] = $this->buildImageMap($desksData);
        }else{
            $this->messages->add("Unknown id", "error");
            redirect('/merchant/dashboard');
        }
        
        $this->viewData['header'] = $this->load->view('merchant/main/header', $this->data,true);
        $this->viewData['menu'] = $this->load->view('merchant/main/menu', $this->data,true);        
        $this->viewData['sidebar'] = $this->load->view('merchant/main/sidebar', $this->data,true);
        $this->viewData['message'] = $this->load->view('merchant/main/message', $this->data,true);
        $this->viewData['content'] = $this->load->view('merchant/plans/plan_map', $this->data,true);
        $this->viewData['footer'] = $this->load->view('merchant/main/footer', $this->data,true);
        $this->load->view('admin/main/content', $this->viewData);
    }
    
    public function show_desks_print($mapId = false)
    {
        settype($mapId, 'integer');
        $this->data = array();
        $mapData = $this->maps_model->getMapDataById($mapId);
        if(empty($mapData)){
            $this->messages->add("This location has no map", "error");
            redirect('/merchant/dashboard/index');
        }
        $this->data['map'] = $mapData['source'];
        $this->data['mapId'] = $mapData['id'];
        $this->data['locationId'] = $mapData['location_id'];
        $mapData = $this->plans_model->getMapDesksData($mapId);

        $this->data['imageMap'] = $this->buildImageMap($mapData);
        $this->data['print_media'] = 1;
        $this->data['print_button'] = '
                    <a href="#" class="print-button" onclick="window.print();"><i class="icon icon-print"></i>Print this page</a><br>
                    <a class="print-button" href="/merchant/plans/show_desks/'.$this->data['locationId'].'" class="print-preview"><i class="icon icon-back"></i>Go Back</a> ';
        $this->viewData['header'] = $this->load->view('merchant/main/print_header', $this->data,true);
        $this->viewData['content'] = $this->load->view('merchant/plans/plan_map_print', $this->data,true);
        $this->load->view('merchant/main/content', $this->viewData);
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
                                href="javascript:show_details('.(isset($desk['company_desk_id'])?$desk['company_desk_id']:'').')" 
                                alt="'.(isset($desk['company_name'])?$desk['company_name']:'').' Desk: '.(isset($desk['id'])?$desk['id']:'').'"
                                >';
        }
        return $areaHtml;
    }
    
    public function getDeskDetails(){
         $this->data = array();
         $this->data['companyDeskId'] = $this->input->post('companyDeskId',true);
         settype($this->data['companyDeskId'], 'integer');
         $this->data['companyDeskData'] = $this->plans_model->getDeskDetails($this->data['companyDeskId']);
         $this->data['companyUsers'] = $this->users_model->getCompanyUsers($this->data['companyDeskData']['company_id']);
         $this->load->view('merchant/plans/desk_details', $this->data);
    }
    
}