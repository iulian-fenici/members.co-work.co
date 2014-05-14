<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Booking the <?php echo $room_name;?> created</title>
</head>
<body bgcolor="#f2f3f4">
    <table cellpadding="0" callspacing="0" border="0" width="550" align="center" bgcolor="#fdfdfe" style="font-family: Helvetica, arial; font-size: 12px;">
<tr>
<td>
</td>
</tr>
        <tr>
            <td>
                 User <?php echo $username;?>,<br><br>
                 Booked the <?php echo $room_name;?> located at <?php echo $location_name;?> from <?php echo date('g:i a d/m/Y', strtotime($from));?>.
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