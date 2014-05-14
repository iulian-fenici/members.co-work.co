<?php
  
function _getDateTo($from,$duaration){
    $duration_time = explode(':', $duaration);
    $h = isset($duration_time[0])&&!empty($duration_time[0])?'+ '.$duration_time[0].' hour':false;
    $m = isset($duration_time[1])&&!empty($duration_time[1])?' + '.$duration_time[1].' min':false;
    return date('Y-m-d H:i:s', strtotime(($h?$h:'').($m?$m:''),strtotime($from)));
}

function checkBookingInterval($exist_from, $exist_duration, $exist_to, $from, $to) {
    if($exist_to == '0000-00-00 00:00:00'){
        $exist_to = _getDateTo($exist_from, $exist_duration);
    }

    $ts1 = strtotime($exist_from);
    $ts2 = strtotime($exist_to);
    $ts3 = strtotime($from);
    $ts4 = strtotime($to);
    $overlap = max($ts1, $ts3) - min($ts2, $ts4);
    if($overlap < 0){
        return false;
    }
    else {
        return true;
    }
}
function checkNextAvailableBooking($exist_from, $exist_duration, $exist_to, $from,$duration, $to){
    $result = false;
    while(!$result){
        if(checkBookingInterval($exist_from, $exist_duration, $exist_to, $from, $to)){
            $result = array('from'=>$from,'to'=>$to);
        }
        $from =_getDateTo($from,'00:15');
        $to =_getDateTo($to,'00:15');
    }
    return $result;
}
function checkInlineRoomsBooking($dataArr,$options,$roomsCount = 2){
    $bookingsInlineCount = 0;
    foreach ($dataArr as $key => $room){
            if(!empty($room['bookings'] )){
                foreach ($room['bookings'] as $k => $v){
                    if(!checkBookingInterval($v['from'], $v['duration'], $v['to'], $options['dateFrom'], $options['dateTo'])){
                        $bookingsInlineCount++;
                        break;
                    }
                    
                }
             }
              
    }
    if($bookingsInlineCount+1 > $roomsCount){
        return false;
    }else{
        return true;
    }
}

function checkBookingForFurtherTime($dataArr, $options, $furtherHours = 2) {
    if(empty($dataArr))
        return true;    

    $locationId = 0;
    if(isset($options['location_id'])&&!empty($options['location_id'])){
        $locationId = (int)$options['location_id'];
    }
    
    foreach ($dataArr as $key=>$room){
        if(!empty($room['bookings'])){
            foreach ($room['bookings'] as $k => $v){
                $res = datesDiff($v['from'], $v['to'], $options['dateFrom'], $options['dateTo']);
                if($res < $furtherHours ){
                    if(!empty($locationId)&&$locationId == 2){
                        if($options['room_id'] == $v['room_id']){
                            return false;
                        }
                    }else{
                       return false;
                    }
                }
            }
        }
    }
    return true;
}

function checkBookingPassedTime($options) {
  
    $dateNow = date('Y-m-d H:i:s');
    if(strtotime($dateNow) > strtotime($options['dateFrom'])){
        return false;
    }else{
        return true;
    }
}

function checkBookingLength($options, $isBusinessTime = false, $length = 2) {

    if(!$isBusinessTime){
        return true;
    }
    else {
        $dateStart = getBookingDateStart($options['dateFrom']);
        $dateEnd = getBookingDateEnd($options['dateTo']);
        $ts1 = strtotime($dateStart);
        $ts2 = strtotime($dateEnd);
        $ts3 = strtotime($options['dateFrom']);
        $ts4 = strtotime($options['dateTo']);
        
        if($ts3<$ts1){
            $ts3 = $ts1;
        }
        if($ts4>$ts2){
            $ts4 = $ts2;
        }
        $diff = $ts4 - $ts3;
        if($diff<0)
            $diff=$diff*(-1);
        
        if($diff > ($length * 3600)){
            return false;
        }
        else {
            return true;
        }
    }
}

function getBookingDateEnd($date,$endTime = '18:59'){
    return date('Y-m-d',strtotime($date)).' ' .$endTime.':00';
}
function getBookingDateStart($date,$startTime = '08:01'){
    return date('Y-m-d',strtotime($date)).' ' .$startTime.':00';
}
function checkBookingBusinessTime($options, $startTime = '08:01',$endTime = '18:59') {
    $dateStart = getBookingDateStart($options['dateFrom']);
    $dateEnd = getBookingDateEnd($options['dateTo']);
    if(isWeekend($options['dateFrom'])){
        return false;
    }
    $ts1 = strtotime($dateStart);
    $ts2 = strtotime($dateEnd);
    $ts3 = strtotime($options['dateFrom']);
    $ts4 = strtotime($options['dateTo']);
    $overlap = max($ts1, $ts3) - min($ts2, $ts4);
    if($overlap > 0){
        return false;
    }
    else {
        return true;
    }
}
function isWeekend($date) {
    return (date('N', strtotime($date)) >= 6);
}
function hoursToSecods ($hour) { // $hour must be a string type: "HH:mm:ss"
    $parse = array();
    if (!preg_match ('#^(?<hours>[\d]{2}):(?<mins>[\d]{2})$#',$hour,$parse)) {
         // Throw error, exception, etc
         return 0;
    }
    return (int) $parse['hours'] * 3600 + (int) $parse['mins'] * 60;
}

function datesDiff($time1From, $time1To, $time2From, $time2To, $format = 'h') {
    $time1From = strtotime($time1From);
    $time2From = strtotime($time2From);
    $time1To = strtotime($time1To);
    $time2To = strtotime($time2To);

    if (($time1From == $time2From) || ($time1From > $time2From && $time1From < $time2To) || ($time2From > $time1From && $time2From < $time1To) || ($time1To == $time2From) ) {
        return 0;
    }
    if ($time2From > $time1To) {
        $diff = $time1To - $time2From;
    } else {
        $diff = $time2To - $time1From;
    }

    if ($diff < 0)
        $diff = $diff * (-1);

    if ($format == 'h') {
        return $diff / 3600;
    }
}
function checkBookingAvailableForCancel($to)
{
    $now = time();
    //$checkDate = strtotime('-1 hours', strtotime($from));
    $checkDate = strtotime($to);
    if ($now < $checkDate)
            return true;
    else 
        return false;
}

function checkBookingForNotificationOnCancel($from)
{
    $now = time();
    $checkDate = strtotime($from);
    
    if ($now < $checkDate)
            return true;
    else 
        return false;
}

function convertTime($dec)
{
    $hour =(int) floor($dec);
    $min = (int) round(60*($dec - $hour));
    return array('h'=>$hour,'m'=>$min);
}

function checkDuration($start, $duration)
{
    $d_start = strtotime(_getDateTo($start,$duration));
    $d_end      = strtotime(date('Y-m-d H:i:s',strtotime(date('Y-m-d',strtotime($start)).' 23:59:59')));
    if ($d_end+1<=$d_start)
        return false;
    else {
        return true;
    }

}

function checkDurationValue($duration)
{

    $hourRes = true;
    $minRes = true;
    if (strpos($duration, ':') !=false)
    {
        $splitstr = explode(':',$duration);
        $hours = (int)$splitstr[0];
        $mins = (int)$splitstr[1];
    }
    else {
        $hours = (int)$duration;
    }
    if (isset($hours))
    {
        if ($hours > 24 || $hours < 0)
        {
            $hourRes = false;
        }
    }
    if (isset($mins))
    {
        if ($mins > 60 || $mins < 0)
        {
            $minRes = false;
        }
    }

    return $minRes&&$hourRes;
        
}