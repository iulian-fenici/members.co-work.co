<div class="row-fluid">
    <div class="span12 widget clearfix">
        <div class="widget-header">
            <span>
                <i class="icon-user"></i>
                Sign Up
            </span>
        </div>
        <div class="widget-content">
            <form method="post" action="/merchant/signup" class="form-horizontal">
                <input type="hidden" name="company_id" value="<?php echo isset($company_id)&&!empty($company_id)?$company_id:'';?>"/>
                <?php $first_nameError = form_error('first_name', ' ', ' '); ?>
                <div class="control-group">
                    <label class="control-label" for="first_name">First Name</label>
                    <div class="controls">
                        <input type="text" id="first_name" name="first_name" placeholder="First Name" value="<?php echo set_value('first_name', ''); ?>">
                        <?php if (isset($first_nameError)): ?>
                            <span class="help-block error"><?php echo $first_nameError; ?></span>
                        <?php endif; ?>
                    </div>
                </div>

                <?php $last_nameError = form_error('last_name', ' ', ' '); ?>
                <div class="control-group">
                    <label class="control-label" for="last_name">Last Name</label>
                    <div class="controls">
                        <input type="text" id="last_name" name="last_name" placeholder="First Name" value="<?php echo set_value('last_name', ''); ?>">
                        <?php if (isset($last_nameError)): ?>
                            <span class="help-block error"><?php echo $last_nameError; ?></span>
                        <?php endif; ?>
                    </div>
                </div>

                <?php $emailError = form_error('email', ' ', ' '); ?>
                <div class="control-group">
                    <label class="control-label" for="email">Email</label>
                    <div class="controls">
                        <input type="text" name="email" id="email" placeholder="Email" value="<?php echo set_value('email', ''); ?>">
                        <?php if (isset($emailError)): ?>
                            <span class="help-block error"><?php echo $emailError; ?></span>
                        <?php endif; ?>
                    </div>
                </div>

                <?php $phoneError = form_error('phone', ' ', ' '); ?>
                <div class="control-group">
                    <label class="control-label" for="phone">Phone</label>
                    <div class="controls">
                        <input type="text" name="phone" id="email" placeholder="Phone" value="<?php echo set_value('phone', ''); ?>">
                        <?php if (isset($emailError)): ?>
                            <span class="help-block error"><?php echo $phoneError; ?></span>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="control-group">
                    <label class="control-label" for="company">Company</label>
                    <div class="controls">
                        <input type="text" name="company" value="<?php echo isset($company) && !empty($company)?$company:'';?>" readonly="readonly"/>
                        <?php $companyIdError = form_error('company_id', ' ', ' '); ?>
                        <?php if (isset($companyIdError)): ?>
                            <span class="help-block error"><?php echo $companyIdError; ?></span>
                        <?php endif;?>                 
                    </div>
                </div>

                <?php $passwordlError = form_error('password', ' ', ' '); ?>
                <div class="control-group">
                    <label class="control-label" for="password">Password</label>
                    <div class="controls">
                        <input type="password" name="password" id="password" placeholder="Password" value="<?php echo set_value('password', ''); ?>">
                        <?php if (isset($passwordlError)): ?>
                            <span class="help-block error"><?php echo $passwordlError; ?></span>
                        <?php endif; ?>
                    </div>
                </div>

                <?php $password_confirmError = form_error('password_confirm', ' ', ' '); ?>
                <div class="control-group">
                    <label class="control-label" for="password_confirm">Confirm Password</label>
                    <div class="controls">
                        <input type="password" name="password_confirm" id="confirm_password" placeholder="Confirm Password" value="<?php echo set_value('password_confirm', ''); ?>">
                        <?php if (isset($confirm_passwordlError)): ?>
                            <span class="help-block error"><?php echo $password_confirmError; ?></span>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="control-group">
                    <div class="controls">
                        <!--            <button type="submit" id="signup" name="signup" class="btn btn-primary">Sign Up</button>-->
                        <?php echo form_submit('signup', 'Sign Up', 'class="uibutton confirm"'); ?>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>