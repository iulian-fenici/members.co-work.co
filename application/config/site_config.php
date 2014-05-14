<?php

$config['db_filters'] = array(
    'booking_list' => array(
        'room_name' => 'r.name',
        'location_name' => 'l.name',
        'company_name' => 'c.name',
        'room_name' => 'r.name',
        'from' => 'b.from',
        'to' => 'b.to',
        'duration' => 'b.duration',
        'user' =>'u.username',
        'location_id'=>'l.id',
        'user_id'=>'u.id',
        'for_user_id'=>'u1.id',
        'room_id'=>'r.id',
        'company_id'=>'c.id',
    ),
    'users_list' => array(
        'user_name' => 'u.username',
        'company_name' => 'c.name',
        'user_email' => 'u.email',
        'company_id' => 'c.id',
        
    ),
    'admins_list' => array(
        'user_name' => 'u.username',
        'user_email' => 'u.email',
    ),
    'companies_list' => array(
        'company_name' => 'c.name',
        'company_description' => 'c.description',
        'company_location' => 'l.name',
        'company_location_id' => 'l.id',
    ),
    'rooms_list' => array(
        'room_name' => 'r.name',
        'room_description' => 'r.description',
        'room_location' => 'l.name',
    ),
    'locations_list' => array(
        'location_name' => 'l.name',
        'location_description' => 'l.description',
    ),
    'announcements_list' => array(
        'announcement_title' => 'a.title',
        'announcement_description' => 'a.description',
        'date_from' => 'a.date_from'
    ),
);

//1 - straight, 0 - like (by default)

$config['db_filters_rules'] = array(
    'from' => 3,
    'to' => 4,
    'location_id'=>1,
    'user_id'=>1,
    'for_user_id'=>1,
    'room_id'=>1,
    'company_id'=>1,
);
// Defines search by drop-down list
$config['form_filters'] = array(
    'users_list' => array(
        'user_name' => 'Username',
        'company_name' => 'Company',
        'user_email' => 'Email',
    ),
    'admins_list' => array(
        'user_name' => 'Username',
        'user_email' => 'Email',
    ),
    'companies_list' => array(
        'company_name' => 'Company Name',
        'company_description' => 'Company Description',
        'company_location' => 'Company Location',
    ),
    'rooms_list' => array(
        'room_name' => 'Room Name',
        'room_description' => 'Room Description',
        'room_location' => 'Room Location',
    ),
    'locations_list' => array(
        'location_name' => 'Location Name',
        'location_description' => 'Location Description',
    ),
    'announcements_list' => array(
        'announcement_title' => 'Announcement Title',
        'announcement_description' => 'Announcement Description',
    ),
    
);

$config['admin_notication_email'] = 'donotreply@co-work.co';
$config['company_name'] = 'Members Co-Work';