<?php

function _sendEmail($from, $to, $msgToSend, $subject) {
    $CI = & get_instance();
    $CI->load->library('email');

    $CI->email->clear();
    $CI->email->set_newline("\r\n");
    $CI->email->from($from);
    $CI->email->to($to);
    $CI->email->subject($subject);
    $CI->email->message($msgToSend);

    return $CI->email->send();
}

function notify($notificationData, $messageData) {
    $ci = & get_instance();
    $admin_template = false;
    $saveNotificationData = true;
    switch ($notificationData['type']) {
        case 1:     // 1 hour before
            $template = 'emails/booking_notification';
            $subject = $messageData['room_name'] . ' - ' . date('H:i', strtotime($messageData['from'])) . '-' . date('H:i', strtotime($messageData['to'])) . ' today';
            break;
        case 2:     // Create booking
            $template = 'emails/creation_booking_notification';
            $admin_template = 'emails/creation_booking_admin_notification';
            $subject = 'Meeting room booking confirmation';
            break;
        case 3:     // Cancel booking
            $template = 'emails/cancel_booking_notification';
            $admin_template = 'emails/cancel_booking_admin_notification';
            $subject = 'Cancel booking notification';
            break;
        case 4:     // create Booking by admin
            $template = 'emails/create_booking_by_admin_notification';
            $subject = 'Create booking by admin notification';
            break;
        case 5:     // edit Booking by admin
            $template = 'emails/edit_booking_by_admin_notification';
            $subject = 'Edit booking by admin notification';
            break;
        case 6:     // delete Booking by admin
            $template = 'emails/cancel_booking_by_admin_notification';
            $subject = 'Delete booking by admin notification';
            break;
        case 7:     // delete Booking by admin
            $template = 'emails/announcement_create_admin';
            $subject = 'Intranet: '.$messageData['title'];
            $saveNotificationData = false;
            break;
        case 8:     // delete Booking by admin
            $template = 'emails/announcement_create_user';
            $subject = 'Intranet: '.$messageData['title'];
            $saveNotificationData = false;
            break;
    }
    // if templates exists
    
    if(!file_exists(FCPATH . 'application/views/' . $template . '.php')){
        return false;
    }
    if($admin_template){
        if(!file_exists(FCPATH . 'application/views/' . $admin_template . '.php')){
            return false;
        }
    }

    if($saveNotificationData){
        $ci->load->model('notifications_model');
        $ci->notifications_model->insert($notificationData);
    }
    $message = $ci->load->view($template, $messageData, true);

    if(ENVIRONMENT == 'development'){
        log_message('error', 'Send email -> admin_notication_email = ' . print_r($ci->config->item('admin_notication_email'), true) . ' | $notificationData = ' . print_r($notificationData, true) . ' | $messageData = ' . print_r($messageData, true));
    }
    else {
        $res = _sendEmail($ci->config->item('admin_notication_email'), $messageData['email'], $message, $subject);
        if($admin_template){
            $res = _sendEmail($ci->config->item('admin_notication_email'), $ci->config->item('admin_notication_email'), $ci->load->view($admin_template, $messageData, true), $subject);
        }
    }
}