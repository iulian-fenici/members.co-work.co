<link rel="stylesheet" href="<?php echo base_url(); ?>css/auth/style.css" type="text/css" media="screen, projection" />
<script type="text/javascript" src="<?php echo base_url();?>js/auth/jquery.placeholder.1.2.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $("#forgot_password_form").validate({
            rules:{
                email: {
                    required: true,
                    email: true
                }
            },
            messages:{
                email:{
                    required: 'Email is required field',
                    email: 'Please input valid email address'
                }
            },
            errorPlacement: function(error, element) {
                error.appendTo( element.next());
            }
        });
                   

    });
    function backToLogin()
    {
        window.location = '/auth/login';
    }
</script>
<div id="successLogin"></div>
<div class="text_success"><img src="/templates/ziceadmin/images/loadder/loader_green.gif"  alt="ziceAdmin" /><span>Please wait</span></div>
    
<div id="login" >
    <div class="inner clearfix">
        <div class="logo" ><img src="/templates/ziceadmin/images/logo/logo.gif" alt="ziceAdmin" /></div>
        <div class="formLogin">
            <form name="formLogin" action="<?php echo site_url(); ?>/auth/forgot_password" id="formLogin" method="post">
                <button type="button" class="btn" id="backLogin" onclick="backToLogin();"><i class="icon-caret-left"></i> Back to Login </button>
                <div style="width: 93%; text-align: center;padding: 12px 15px 12px 12px;">
                    Don't worry. Enter the email address associated with your account so we can send you the password reset instructions.
                </div>
                <div class="tip">
                    <?php echo form_input($email); ?>
                </div>
                <div class="tip">
                    <button type="submit" name="submit" class="uibutton large" id="but_login">Send Reset Instructions</button>
                </div>
            </form>
        </div>
    </div>
    <div class="shadow"></div>
</div>
    
<!--Login div-->
<div class="clear"></div>