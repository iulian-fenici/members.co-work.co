<script type="text/javascript">
    $(document).ready(function() {
//            $("#login_form").validate({
//                    rules:{
//                        identity: {
//                            required: true,
//                            email: true
//                        },
//                        password:{
//                            required: true
//                        }
//                    },
//                    messages:{
//                        identity:{
//                            required: 'Login is required field',
//                            email: 'Please input valid email address'
//                        },
//                        password: 'Password is required field'
//                    },
//                    errorPlacement: function(error, element) {
//                        error.appendTo( element.next());
//                    }
//           });
    });
    function forget_password()
    {
        window.location = "<?php echo site_url() . 'auth/forgot_password'; ?>";
    }
</script>
<div id="successLogin"></div>
        <div class="text_success"><img src="/templates/ziceadmin/images/loadder/loader_green.gif"  alt="ziceAdmin" /><span>Please wait</span></div>
        
        <div id="login" >
          <div class="inner clearfix">
          <div class="logo" ><img src="/templates/ziceadmin/images/logo/logo.gif" alt="ziceAdmin" /></div>
          <div class="formLogin">
         <form name="formLogin" action="<?php echo site_url();?>auth/login" id="formLogin" method="post">
         <?php //echo form_open("auth/login", 'id="formLogin" name="formLogin"'); ?>
            <?php if(isset($message) && !empty($message)): ?>
             <div style="width: 85%;margin: 0 auto;">
                <?php echo isset($message) ? $message : ''; ?>
             </div>
            <?php endif; ?>
              <?php $identityError = form_error('identity', ' ', ' '); ?>
                <div class="tip">
                    <?php echo form_input($identity); ?>
                    <?php $identityError = form_error('identity', ' ', ' '); ?>
                          <?php if (isset($identityError)): ?>
                            <span class="help-block error"><?php echo $identityError; ?></span>
                    <?php endif;?>
                </div>
                <div class="tip">
<!--                      <input name="password" type="password" id="password"   title="Password"  />-->
                    <?php echo form_input($password); ?>
                    <?php $passwordError = form_error('password', ' ', ' '); ?>
                          <?php if (isset($passwordError)): ?>
                            <span class="help-block error"><?php echo $passwordError; ?></span>
                    <?php endif;?>
                    
                </div>
      
                <div class="loginButton">
                  <div style="float:left; margin-left:-9px;">
                      <input type="checkbox" id="on_off" name="remember" class="on_off_checkbox"  value="1" onclick="doCheckbox(this);" checked="checked"/>
                      
                      <span class="f_help">Remember me</span>
                  </div>
                  <div class=" pull-right" style="margin-right:-8px;">
                      <div class="btn-group">
                        <button type="submit" name="submit" class="btn" id="but_login">Login</button>
                        <button type="button" class="btn" id="forgetpass" onclick="forget_password();"> Forget Pass</button>
                      </div>
<!--                     <span class="f_help">or <a href="#" id="createacc">Create Account</a></span>-->
                  </div>
                  <div class="clear"></div>
                </div>
      
          </form>
              
<!--          <form id="createaccPage" method="post" action="">
                <div class="tip">
                      <input name="email_acc" type="text" class="inputtext"  placeholder="Email"  title="Email"   />
                </div>
                <div class="tip">
                      <input name="fname_acc"  type="text" class="inputtext"  placeholder="First name" title="First name"  />
                </div>
                <div class="tip">
                      <input name="lname_acc"  type="text" class="inputtext" placeholder="Last name" title="Last name"   />
                </div>
                <div class="tip">
                      <input name="password_acc" type="text" class="inputtext" placeholder="Password" title="Password"  />
                </div>
                <div class="tip">
                      <input name="birthday_acc"  type="text" class="inputtext"  placeholder="Date of Birth"  title="Date of Birth"  />
                </div>
                <div class="loginButton" align="center">
                        <button type="button" class="btn" id="backLogin"><i class="icon-caret-left"></i> Back </button>
                        <button type="button" class="btn btn-danger white " onClick="$('#createaccPage').submit();"><i class="icon-unlock"></i> Regester </button>
                </div>
          </form>-->
          </div>
        </div>
          <div class="shadow"></div>
        </div>
        
        <!--Login div-->
        <div class="clear"></div>

        
        <!-- Link JScript-->
        <script type="text/javascript" src="/templates/ziceadmin/components/ui/jquery.ui.min.js"></script>
        <script type="text/javascript" src="/templates/ziceadmin/components/form/form.js"></script>
<!--        <script type="text/javascript" src="/js/ziceadmin/login_php.js"></script>-->
		<script type="text/javascript" >
        $(document).ready(function () {	 
                $('#createacc').click(function(e){
                    $('#login').animate({   height: 350, 'margin-top': '-200px' }, 300);	
                    $('.formLogin').animate({   height: 240 }, 300);
                    $('#createaccPage').fadeIn();
                    $('#formLogin').hide();
                });
                $('#backLogin').click(function(e){
                    $('#login').animate({   height: 254, 'margin-top': '-148px' }, 300);	
                    $('.formLogin').animate({   height: 150 }, 300);
                    $('#formLogin').fadeIn();
                    $('#createaccPage').hide();
                });			
        });		
        </script>
        </body>
        </html>