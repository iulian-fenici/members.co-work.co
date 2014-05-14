<?php

if(!defined('BASEPATH'))
    exit('No direct script access allowed');

class Notification extends My_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model(array('users_model','notifications_model', 'announcements_model'));
        $this->load->helper('send_message');
    }

    public function booking_notification() {
        log_message('error', "Notification start.");
        $userListForNotification = $this->notifications_model->getUserListForBookingNotification();

        log_message('error', "Notification. userListForNotification=" . print_r($userListForNotification, true));
        if(isset($userListForNotification) && !empty($userListForNotification))
            foreach ($userListForNotification as $bookingData){
                // store notifications
                if(isset($bookingData) && !empty($bookingData['user_id'])){
                    $notificationData = array(
                        'user_id' => $bookingData['user_id'],
                        'booking_id' => $bookingData['booking_id'],
                        'type' => 1,
                        'created_at' => date('Y-m-d H:i:s')
                    );
                    notify($notificationData, $bookingData);
                }
                log_message('error', "NotificationData=" . print_r($notificationData, true));
            }
    }

    public function announcement_notification() {
        $announcementsNotification = $this->announcements_model->getNotificationsAnnouncementsArr();
        log_message('error', "Notification. announcementsNotification=" . print_r($announcementsNotification, true));
         
        $this->notificationUsersArr = array();
        if(!empty($announcementsNotification)){
            foreach ($announcementsNotification as $announcement){
                $this->notificationUsersArr = array();
                $this->_setlocationUsersArr($announcement["locations_ids"]);
                $this->_setCompanyUsersArr($announcement["companies_ids"]);
                $this->_setUsersArr($announcement["users_ids"]);
                $this->notificationUsersArr = array_unique($this->notificationUsersArr,SORT_STRING);
                log_message('error', "Notification. this->notificationUsersArr=" . print_r($this->notificationUsersArr, true));
                $this->_setSentStatus($announcement);
                $this->_sendUsersNotification($announcement);
            }
        }
    }

    private function _setCompanyUsersArr($data = null) {
        if(empty($data)){
            return false;
        }
        $arr = explode(',',$data);
        $arr = array_unique($arr);
        foreach($arr as $compnayId){
            $usersArr = $this->users_model->getCompanyUsersEmailsArr($compnayId);
            $this->notificationUsersArr = array_merge($this->notificationUsersArr,$usersArr);
        }
    }

    private function _setlocationUsersArr($data = null) {
        if(empty($data)){
            return false;
        }
        $arr = explode(',',$data);
        $arr = array_unique($arr);
        foreach($arr as $locationId){
            $usersArr = $this->users_model->getLocationUsersEmailsArr($locationId);
            $this->notificationUsersArr = array_merge($this->notificationUsersArr,$usersArr);
        }
    }

    private function _setUsersArr($data = null) {
        if(empty($data)){
            return false;
        }
        $arr = explode(',',$data);
        $arr = array_unique($arr);
        $usersArr = $this->users_model->getUsersEmailsArr($arr);
        $this->notificationUsersArr = array_merge($this->notificationUsersArr,$usersArr);
    }
    
    private function _sendUsersNotification($announcement){
        if(empty($announcement)||empty($this->notificationUsersArr)){
            return false;
        }
        $messageData = array(
            'title'=>$announcement['title'],
            'description'=>$announcement['description'],
        );
        
        if($announcement['admin_announcement']==1){
            $notificationData['type'] = 7;
        }elseif($announcement['admin_announcement']==0){
            $notificationData['type'] = 8;
            $messageData['username'] = $announcement['username'];
            $messageData['company_name'] = $announcement['company_name'];
            $messageData['user_id'] = $announcement['user_id'];
        }
      
        foreach($this->notificationUsersArr as $email){
            $messageData['email'] = $email;
            notify($notificationData, $messageData);
        }
        
    }
    private function _setSentStatus($announcement){
        $updateAnnouncementData = array(
            'send_email' => 2
        );
        $res = $this->announcements_model->update($announcement['id'], $updateAnnouncementData);
    }

}
