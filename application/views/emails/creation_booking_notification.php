<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Booking the <?php echo $room_name;?></title>
</head>
<body bgcolor="#f2f3f4">
    <table cellpadding="0" callspacing="0" border="0" width="550" align="center" bgcolor="#fdfdfe" style="font-family: Helvetica, arial; font-size: 12px;">
<tr>
<td>
</td>
</tr>
        <tr>
            <td>
                 Hello <?php echo $username;?>,<br><br>
                 We booked the <?php echo $room_name;?> located at <?php echo $location_name;?> from <?php echo date('g:i a',strtotime($from)).' - '.date('g:i a',strtotime($to));?> on <?php echo date('d/m/Y',strtotime($from));?> for you.
                 Should you need to cancel this booking, please do so by clicking <a href="<?php echo base_url().'cancel_booking/index/'.  base64_encode(json_encode(array('booking_id'=>$booking_id,'user_id'=>$user_id)))?>">here</a>.
            </td>
        </tr>
        <tr>
            <td>
               <b>
                   <br>
                       Have a nice day,
                    <br>
                </b>
                <br>
                <br>
                    Co-Work
            </td>
        </tr>
    </table>
</body>
</html>
