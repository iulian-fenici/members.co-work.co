<script type="text/javascript">
    $(document).ready(function(){
        generateUrl();
    });

    function generateUrl()
    {
            var str = '&#12(&ghghghjgg';
            var company_id = $("#company_id").val();
         
            if (typeof company_id == 'undefined' || company_id == "")
            {
                alert('Please select company first');
                return false;
            }
            var base_url = "<?php echo isset($base_url)&&!empty($base_url)?$base_url:'booking2.appteka.net';?>";
            var encoded = Base64.encode(str+$('#company_id').val());
            var content = '<h3>Registration link: <a href="'+base_url+'merchant/signup?id='+encoded+'">'+base_url+'merchant/signup?id='+encoded+'</a></h3>';
            $('#registrationlink3').html(content);
    }
    
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
            <form method="post" action="/merchant/registration/send_invite" class="form-horizontal">
                <input type="hidden" id="company_id" name="company_id" value="<?php echo $company_id;?>"/>
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