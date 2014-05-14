
<div id="successLogin"></div>
<div class="text_success"><img src="/templates/ziceadmin/images/loadder/loader_green.gif"  alt="ziceAdmin" /><span>Please wait</span></div>
<?php if (isset($errors) && $errors)
{
    $style = 'style="height: 640px;"';
}
 else {
    $style = '';
}
?>
<div id="registration" <?php echo $style;?>>
    <div class="inner clearfix" <?php echo $style;?>>
        <div class="logo" ><img src="/templates/ziceadmin/images/logo/logo.gif" alt="ziceAdmin" /></div>
        <div class="formLogin">

          <form  action="/merchant/signup" id="createaccPage" method="post">
              <input type="hidden" name="company_id" value="<?php echo isset($company_id)&&!empty($company_id)?$company_id:'';?>"/>
              <input type="hidden" name="company" value="<?php echo isset($company)&&!empty($company)?$company:'';?>"/>
                <?php if (isset($company)&&!empty($company)):?>
                <div class="tip">
                   Company: <?php echo $company;?>
                </div>
                <?php endif;?>
                <div class="tip">
                      <input name="first_name"  type="text" class="inputtext"  placeholder="First name" title="First name" value="<?php echo set_value('first_name', ''); ?>" />
                          <?php $first_nameError = form_error('first_name', ' ', ' '); ?>
                          <?php if (isset($first_nameError)): ?>
                            <span class="help-block error"><?php echo $first_nameError; ?></span>
                         <?php endif;?>
                </div>
                <div class="tip">
                      <input name="last_name"  type="text" class="inputtext" placeholder="Last name" title="Last name" value="<?php echo set_value('last_name', ''); ?>"  />
                      <?php $last_nameError = form_error('last_name', ' ', ' '); ?>
                          <?php if (isset($last_nameError)): ?>
                            <span class="help-block error"><?php echo $last_nameError; ?></span>
                      <?php endif;?>
                </div>
                <div class="tip">
                      <input name="email" type="text" class="inputtext"  placeholder="Email"  title="Email" value="<?php echo set_value('email', ''); ?>"  />
                      <?php $emailError = form_error('email', ' ', ' '); ?>
                          <?php if (isset($emailError)): ?>
                            <span class="help-block error"><?php echo $emailError; ?></span>
                      <?php endif;?>
                </div>
                <div class="tip">
                      <input name="phone" type="text" class="inputtext" placeholder="Phone" title="Phone" value="<?php echo set_value('phone', ''); ?>" />
                      <?php $phoneError = form_error('phone', ' ', ' '); ?>
                          <?php if (isset($phoneError)): ?>
                            <span class="help-block error"><?php echo $phoneError; ?></span>
                      <?php endif;?>
                      
                </div>
                <div class="tip">
                      <input name="password" type="password" class="inputtext" placeholder="Password" title="Password" value="<?php echo set_value('password', ''); ?>" />
                      <?php $passwordError = form_error('password', ' ', ' '); ?>
                          <?php if (isset($passwordError)): ?>
                            <span class="help-block error"><?php echo $passwordError; ?></span>
                      <?php endif;?>
                </div>
              
                <div class="tip">
                      <input name="password_confirm" type="password" class="inputtext" placeholder="Confirm Password" title="Confirm Password" value="<?php echo set_value('password_confirm', ''); ?>" />
                      <?php $password_confirmError = form_error('password_confirm', ' ', ' '); ?>
                          <?php if (isset($password_confirmError)): ?>
                            <span class="help-block error"><?php echo $password_confirmError; ?></span>
                      <?php endif;?>
                </div>
                <div class="loginButton" align="center">
                        <button type="submit" name="signup" value="signup" class="btn btn-danger white " onClick="$('#createaccPage').submit();"><i class="icon-unlock"></i> Regester </button>
                </div>
          </form>
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

</body>
</html>