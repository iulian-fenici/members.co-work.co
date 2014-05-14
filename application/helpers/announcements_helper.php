<?php

function getAnnouncements($type = 'dashboard', $idsArr) {
    if(empty($idsArr) 
        || (!isset($idsArr['user_id'])||empty($idsArr['user_id']))
        || (!isset($idsArr['company_id'])||empty($idsArr['company_id']))
        || (!isset($idsArr['location_ids'])||empty($idsArr['location_ids']))){
        return false;
    }
    $ci = &get_instance();
    $ci->load->model(array('announcements_model'));

    $announcements = prepareAnnouncements($ci->announcements_model->getAnnouncementsArr());

    switch ($type) {
        case 'dashboard':
            $result = checkAnnouncementsDashboard($announcements,$idsArr);
            break;
    }
    return $result;
}

function checkAnnouncementsDashboard($announcements,$idsArr) {
    if(empty($announcements)||empty($idsArr)){
        return false;
    }
    $result = array();
    $arr1 = array();
    foreach ($announcements as $v){
        if(in_array($idsArr['user_id'], $v['users_ids'])){
            $result[] = $v;
            continue;
        }elseif(in_array($idsArr['company_id'], $v['companies_ids'])){
            $result[] = $v;
            continue;
        }
        //elseif(in_array($idsArr['location_id'], $v['locations_ids'])){
        else{
            $arr1 = array_intersect($idsArr['location_ids'], $v['locations_ids']);
            if(!empty($arr1)){
                $result[] = $v;
                continue;
            }
        }
    }
    return $result;
}

function prepareAnnouncements($announcements) {
    if(empty($announcements)){
        return false;
    }
    if(isset($announcements[0])){        
        foreach ($announcements as $k => $v){

            $announcements[$k]['locations_ids'] = isset($announcements[$k]['locations_ids']) && !empty($announcements[$k]['locations_ids']) ? array_unique(explode(',', $announcements[$k]['locations_ids'])) : array();
            $announcements[$k]['companies_ids'] = isset($announcements[$k]['companies_ids']) && !empty($announcements[$k]['companies_ids']) ? array_unique(explode(',', $announcements[$k]['companies_ids'])) : array();
            $announcements[$k]['users_ids'] = isset($announcements[$k]['users_ids']) && !empty($announcements[$k]['users_ids']) ? array_unique(explode(',', $announcements[$k]['users_ids'])) : array();
        }
    }
    else {
        $announcements['locations_ids'] = isset($announcements['locations_ids']) && !empty($announcements['locations_ids']) ? array_unique(explode(',', $announcements['locations_ids'])) : array();
        $announcements['companies_ids'] = isset($announcements['companies_ids']) && !empty($announcements['companies_ids']) ? array_unique(explode(',', $announcements['companies_ids'])) : array();
        $announcements['users_ids'] = isset($announcements['users_ids']) && !empty($announcements['users_ids']) ? array_unique(explode(',', $announcements['users_ids'])) : array();
    }
    return $announcements;
}


?>
