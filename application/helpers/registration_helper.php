<?php
function register($registrationData)
{
    
    $ci = & get_instance();
    $ci->load->library('ion_auth');
    $username = htmlspecialchars($registrationData['first_name'] . ' ' . $registrationData['last_name']);
    $email = $registrationData['email'];
    $password = $registrationData['password'];

    $additional_data = array('first_name' => $registrationData['first_name'],
        'last_name' => htmlspecialchars($registrationData['last_name']),
        //'company' => htmlspecialchars($registrationData['company']),
        'phone' => $registrationData['phone'],
        'company_id' => $registrationData['company_id'],
    );
//    var_dump($registrationData);
//    var_dump( $additional_data);die;
    $usersGroupId = $ci->ion_auth->getGroupIdByName($registrationData['group']);
    $id = $ci->ion_auth->register($username, $password, $email, $additional_data, array($usersGroupId));
    if ($id)
    {
        $ci->load->helper('send_message');
        $message = $ci->load->view($ci->config->item('email_templates', 'ion_auth').$ci->config->item('email_activate', 'ion_auth'), array('identity' => $email), true);
        _sendEmail($ci->config->item('admin_email', 'ion_auth'), $email, $message, 'Activation Account');    
    }
    return $id;
}
