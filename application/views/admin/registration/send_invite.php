<script type="text/javascript">
    $(document).ready(function(){
        $('#company_id').on('change', function(){
            var str = '&#12(&ghghghjgg';
            var company_id = $('#company_id').val();
            if (company_id == "")
            {
                alert('Please select company first');
                return false;
            }
            var base_url = "<?php echo isset($base_url)&&!empty($base_url)?$base_url:'booking2.appteka.net';?>";
            var encoded = Base64.encode(str+$('#company_id').val());
            var content = '<h3>Registration link: <a href="'+base_url+'merchant/signup?id='+encoded+'">'+base_url+'merchant/signup?id='+encoded+'</a></h3>';
            $('#registrationlink3').html(content);
        });
    });
    
</script>
<div class="row-fluid">
    <div class="span12 widget clearfix">
        <div class="widget-header">
            <span>
                <i class="icon-edit"></i>
                Send Invite
            </span>
        </div>
        <div class="widget-content">
            <div id="registrationlink3">

            </div>
            <form method="post" action="/admin/registration/send_invite" class="form-horizontal">
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

                <?php if(isset($companiesList) && !empty($companiesList)): ?>
                    <div class="control-group">
                        <label class="control-label" for="company_id">Companies</label>
                        <div class="controls">
                            <?php $company_idError = form_error('company_id', ' ', ' '); ?>
                            <?php echo $companiesList; ?>
                            <?php if(isset($company_idError)): ?>
                                <span class="help-block error"><?php echo $company_idError; ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="control-group">
                    <div class="controls">
                        <!--            <button type="submit" id="signup" name="signup" class="btn btn-primary">Sign Up</button>-->
                        <?php echo form_submit('save', 'Send Invite', 'class="uibutton confirm"'); ?>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>