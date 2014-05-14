<?php
function bookingListDateFormat($date)
{
//    if (date('Y-m-d',  strtotime($date)) == date('Y-m-d'))
//    {
//        $date = 'Today at '.date('h:i a',strtotime($date));
//    }
//    elseif (date('Y-m-d',strtotime($date)) == date('Y-m-d',strtotime("+1 day"))) {
//        $date = 'Tomorrow at '.date('h:i a',strtotime($date));
//    }
// else {
//        $date = date('d/m/Y',strtotime($date)) . ' at '.date('h:i a',strtotime($date));
//    }
    $date = date('g:i a',strtotime($date)).' - '.date('d/m/Y',strtotime($date));
    return $date;
}