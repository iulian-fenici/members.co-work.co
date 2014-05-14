<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Announcement <?php echo $title;?></title>
</head>
<body bgcolor="#f2f3f4">
    <table cellpadding="0" callspacing="0" border="0" width="550" align="center" bgcolor="#fdfdfe" style="font-family: Helvetica, arial; font-size: 12px;">
<tr>
<td>
</td>
</tr>
        <tr>
            <td>
                 User <?php echo $username;?> created an announcement with the following text:<br><br>
                 <?php echo $description;?>.
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
                    <?php echo isset($company_name)&&!empty($company_name)?$company_name:'Co-Work';?>
                <br>
                    To contact <?php echo $username;?>, please find the contact details <a href="<?php echo base_url();?>merchant/profile/view_profile/<?php echo $user_id;?>?redirectPage=merchant/profile/view_profile/<?php echo $user_id;?>">here</a>.
            </td>
        </tr>
    </table>
</body>
</html>