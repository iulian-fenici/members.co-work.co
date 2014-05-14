<link rel="stylesheet" href="<?php echo base_url();?>css/jquery-uploader/jquery.fileupload-ui.css" type="text/css" />

<div class="row-fluid">
    
    <div class="span12 widget clearfix">
        <div class="widget-header">
            <span>
                <i class="icon-edit"></i>
                Edit Profile
            </span>
        </div>
        <div class="widget-content">
        <form method="post" action="/merchant/profile/edit_profile" class="form-horizontal">
            
            <div class="control-group">
                <div class="controls">
                    <span class="uibutton confirm large fileinput-button">
                        <i class="icon-plus icon-white"></i>
                        <span>Add photo</span>
                        <input type="file" id="photo-uploader" name="files[]" data-url="/admin/upload/add_user_photo" >
                    </span>
                    
                    <div class="preview">
                        <?php if(isset($userData->thumb_name) && !empty($userData->thumb_name) && file_exists($userData->thumb_rel_path . $userData->thumb_name)): ?>
                            <div class="image-item">
                                <img src="<?php echo base_url(). $userData->thumb_rel_path . $userData->thumb_name; ?>" class="img-polaroid" />
                                <div class="delete-button-container">
                                    <a href="javascript:void(0)" class="delete-file" title="Delete file"><i class="icon-trash"></i></a>
                                </div>
                            </div>
                        <?php endif;?>   
                    </div>
                </div>
                
            </div>   
            <?php $first_nameError = form_error('first_name', ' ', ' '); ?>
            <div class="control-group">
                <label class="control-label" for="first_name">First Name</label>
                <div class="controls">
                    <input type="text" id="first_name" name="first_name" class="input-xlarge" placeholder="First Name" value="<?php echo set_value('first_name', isset($userData->first_name)?$userData->first_name:''); ?>">
                    <?php if(isset($first_nameError)): ?>
                        <span class="help-block error"><?php echo $first_nameError; ?></span>
                    <?php endif; ?>
                </div>
            </div>

            <?php $last_nameError = form_error('last_name', ' ', ' '); ?>
            <div class="control-group">
                <label class="control-label" for="last_name">Last Name</label>
                <div class="controls">
                    <input type="text" id="last_name" name="last_name" class="input-xlarge" placeholder="First Name" value="<?php echo set_value('last_name', isset($userData->last_name)?$userData->last_name:''); ?>">
                    <?php if(isset($last_nameError)): ?>
                        <span class="help-block error"><?php echo $last_nameError; ?></span>
                    <?php endif; ?>
                </div>
            </div>

            <?php $emailError = form_error('email', ' ', ' '); ?>
            <div class="control-group">
                <label class="control-label" for="email">Email</label>
                <div class="controls">
                    <input type="text" name="email" id="email" class="input-xlarge" placeholder="Email" value="<?php echo set_value('email', isset($userData->email)?$userData->email:''); ?>">
                    <?php if(isset($emailError)): ?>
                        <span class="help-block error"><?php echo $emailError; ?></span>
                    <?php endif; ?>
                </div>
            </div>

            <?php $phoneError = form_error('phone', ' ', ' '); ?>
            <div class="control-group">
                <label class="control-label" for="phone">Phone</label>
                <div class="controls">
                    <input type="text" name="phone" id="email" class="input-xlarge" placeholder="Phone" value="<?php echo set_value('phone', isset($userData->phone)&&!empty($userData->phone)?$userData->phone:''); ?>">
                    <?php if(isset($emailError)): ?>
                        <span class="help-block error"><?php echo $phoneError; ?></span>
                    <?php endif; ?>
                </div>
            </div>
            <?php $passwordlError = form_error('password', ' ', ' '); ?>
            <div class="control-group">
                <label class="control-label" for="password">Password</label>
                <div class="controls">
                    <input type="password" name="password" id="password" autocomplete="off" class="input-xlarge" placeholder="Password" value="<?php echo set_value('password', ''); ?>">
                    <?php if(isset($passwordlError)): ?>
                        <span class="help-block error"><?php echo $passwordlError; ?></span>
                    <?php endif; ?>
                </div>
            </div>

            <?php $password_confirmError = form_error('password_confirm', ' ', ' '); ?>
            <div class="control-group">
                <label class="control-label" for="password_confirm">Confirm Password</label>
                <div class="controls">
                    <input type="password" name="password_confirm" id="confirm_password" class="input-xlarge" placeholder="Confirm Password" value="<?php echo set_value('password_confirm', ''); ?>">
                    <?php if(isset($confirm_passwordlError)): ?>
                        <span class="help-block error"><?php echo $password_confirmError; ?></span>
                    <?php endif; ?>
                </div>
            </div>
            <div class="hidden-fields">
                
            </div> 
            <div class="control-group">
                <div class="controls">
                    <?php echo form_submit('save', 'Save', 'class="uibutton confirm"'); ?>
                </div>
            </div>
        </form>
        </div>
    </div>
</div>
<script src="<?php echo base_url();?>js/jquery-uploader/vendor/jquery.ui.widget.js"></script>
<script src="<?php echo base_url();?>js/jquery-uploader/jquery.iframe-transport.js"></script>
<script src="<?php echo base_url();?>js/jquery-uploader/jquery.fileupload.js"></script>
<script src="<?php echo base_url();?>js/jquery-uploader/jquery.fileupload-fp.js"></script>
<script src="<?php echo base_url();?>js/jquery-uploader/jquery.fileupload-ui.js"></script>
<script>
$(function () {
    $('#photo-uploader').fileupload({
        dataType: 'json', 
        autoUpload: true,
        maxFileSize: 5000000,//5MB
        acceptFileTypes: "/(\.|\/)(gif|jpe?g|png)$/i",
        done: function (e, data) {
            $.each(data.result, function (index, file) {
                if(typeof(file.thumbnail_url) !== "undefined"){
                    $('.preview').html('');
                    var template = 
                        '<div class="image-item">'+
                            '<img src="'+file.thumbnail_url+'" class="img-polaroid" />'+
                            '<div class="delete-button-container">'+
                                    '<a href="javascript:void(0)" class="delete-file" data-param="deleted-file" title="Delete file"><i class="icon-trash"></i></a>'+
                            '</div>'+
                        '</div>';
                    $(template).appendTo($('.preview'));
                    $('<input type="hidden" value="'+btoa(JSON.stringify(file))+'" name="uploaded-photo"/>').appendTo($('.hidden-fields'));
                }
            })
        }   
    });
    $('.delete-file').live('click',function(){
        var el = $(this);
        if(confirm('Are you sure you want delete this file')){
            $.ajax({
                    url: "/merchant/profile/delete_user_photo",
                    type: "POST",
                    data:{
                        param:'delete-user-photo'
                    },
                    success: function(data) {
                        $(el).parents('div.image-item').remove();
                        $('.hidden-fields').find('[name="uploaded-photo"]').remove();
                    }
            });
        }
    });
    
});
</script>
