
<div id="successLogin"></div>
<div class="text_success"><img src="/templates/ziceadmin/images/loadder/loader_green.gif"  alt="ziceAdmin" /><span>Please wait</span></div>
    
<?php if (!empty($message)):?>
<?php $style = 'style="height: 362px;"';?>
<?php else:?>
<?php $style = '';?>
<?php endif;?>
<div id="login" <?php echo $style;?>>
    <div class="inner clearfix" <?php echo $style;?>>
        <div class="logo" ><img src="/templates/ziceadmin/images/logo/logo.gif" alt="ziceAdmin" /></div>
        <div class="formLogin">
            <div id="infoMessage" style="color: red; width: 98%;"><?php echo $message;?></div>
            <form name="formLogin" action="<?php echo site_url().'/auth/reset_password/' . $code;?>" id="formLogin" method="post">
                <?php echo form_input($user_id);?>
                <?php echo form_hidden($csrf); ?>
                <div class="tip">
<!--                    <span class="f_help">New Password (at least <?php echo $min_password_length;?> characters long):</span>-->
                    <?php echo form_input($new_password);?>
                </div>
                <div class="tip">
<!--                    <span class="f_help">Confirm New Password:</span>-->
                    <?php echo form_input($new_password_confirm);?>
                </div>
                <div class="tip">
                    <button type="submit" name="submit" class="uibutton large" id="but_login">Change</button>
                </div>
            </form>
        </div>
    </div>
    <div class="shadow"></div>
</div>
    
<!--Login div-->
<div class="clear"></div>             