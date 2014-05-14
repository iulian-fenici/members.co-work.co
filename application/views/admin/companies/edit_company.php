<link rel="stylesheet" href="<?php echo base_url(); ?>css/jquery-uploader/jquery.fileupload-ui.css" type="text/css" />

<div class="row-fluid">
    <div class="span12 widget clearfix">
        <div class="widget-header">
            <span>
                <i class="icon-edit"></i>
                Edit Company
            </span>
        </div>
        <div class="widget-content">
            <form method="post" action="/admin/company/edit_company/<?php echo isset($id) ? $id : 0; ?>" class="form-horizontal">
                <div class="control-group">
                    <div class="controls">
                        <span class="uibutton confirm large fileinput-button">
                            <i class="icon-plus icon-white"></i>
                            <span>Add photo</span>
                            <input type="file" id="photo-uploader" name="files[]" data-url="/admin/upload/add_location_photo" >
                        </span>

                        <div class="preview">
                            <?php if (isset($companyData['thumb_name']) && !empty($companyData['thumb_name']) && file_exists($companyData['thumb_rel_path'] . $companyData['thumb_name'])): ?>
                                <div class="image-item">
                                    <img src="<?php echo base_url() . $companyData['thumb_rel_path'] . $companyData['thumb_name']; ?>" class="img-polaroid" />
                                    <div class="delete-button-container">
                                        <a href="javascript:void(0)" class="delete-file" title="Delete file"><i class="icon-trash"></i></a>
                                    </div>
                                </div>
                            <?php else:?>
                                <div class="image-item">
                                    <img src="/img/main/no_picture.png" class="img-polaroid" />
                                </div>
                            <?php endif; ?>   
                        </div>
                    </div>
                </div>
                
                
                <?php $locationsError = form_error('locations[]', ' ', ' '); ?>
                <?php if (isset($locationsArr) && !empty($locationsArr)): ?>
                    <div class="control-group">
                        <label class="control-label" for="locations">Locations</label>
                        <div class="controls">  

                            <?php foreach ($locationsArr as $location): ?>
                                <span>
                                    <label class="checkbox">
                                        <input type="checkbox" name="locations[]" <?php echo isset($location['checked']) && $location['checked'] ? 'checked="checked"' : ''; ?> value="<?php echo $location['id']; ?>"> <?php echo $location['name']; ?>
                                    </label>
                                </span>
                            <?php endforeach; ?>
                            <?php if (isset($locationsError)): ?>
                                <span class="help-block error"><?php echo $locationsError; ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>
                
                
<!--                <?php //$location_idError = form_error('location_id', ' ', ' '); ?>
                <div class="control-group">
                    <label class="control-label" for="location_id">Locations</label>
                    <div class="controls">
                        <?php //echo $locationsList; ?>
                        <?php //if (isset($location_idError)): ?>
                            <span class="help-block error"><?php// echo $location_idError; ?></span>
                        <?php //endif; ?>
                    </div>
                </div>-->
                
                
                
                
                
                
                <?php $nameError = form_error('name', ' ', ' '); ?>
                <div class="control-group">
                    <label class="control-label" for="name">Name</label>
                    <div class="controls">
                        <input type="text" id="name" name="name" class="input-xlarge" placeholder="Company Name" value="<?php echo set_value('name', isset($companyData['name']) ? _e($companyData['name']) : ''); ?>">
                        <?php if (isset($nameError)): ?>
                            <span class="help-block error"><?php echo $nameError; ?></span>
                        <?php endif; ?>
                    </div>
                </div>
                <?php $descriptionError = form_error('description', ' ', ' '); ?>
                <div class="control-group">
                    <label class="control-label" for="description">Description</label>
                    <div class="controls">
                        <textarea name="description" id="description" class="input-xlarge" placeholder="Company Description"><?php echo set_value('description', isset($companyData['description']) ? _e($companyData['description']) : ''); ?></textarea>
                        <?php if (isset($descriptionError)): ?>
                            <span class="help-block error"><?php echo $descriptionError; ?></span>
                        <?php endif; ?>
                    </div>
                </div>
                <?php $phoneError = form_error('phone', ' ', ' '); ?>
                <div class="control-group">
                    <label class="control-label" for="phone">Phone</label>
                    <div class="controls">
                        <input type="text" id="phone" name="phone" class="input-xlarge" placeholder="Phone" value="<?php echo set_value('phone', isset($companyData['phone']) ? _e($companyData['phone']) : ''); ?>">
                        <?php if (isset($phoneError)): ?>
                            <span class="help-block error"><?php echo $phoneError; ?></span>
                        <?php endif; ?>
                    </div>
                </div>
                <?php $emailError = form_error('email', ' ', ' '); ?>
                <div class="control-group">
                    <label class="control-label" for="email">Email</label>
                    <div class="controls">
                        <input type="text" id="email" name="email" class="input-xlarge" placeholder="Email" value="<?php echo set_value('email', isset($companyData['email']) ? _e($companyData['email']) : ''); ?>">
                        <?php if (isset($emailError)): ?>
                            <span class="help-block error"><?php echo $emailError; ?></span>
                        <?php endif; ?>
                    </div>
                </div>
                <?php $site_urlError = form_error('site_url', ' ', ' '); ?>
                <div class="control-group">
                    <label class="control-label" for="site_url">Web Site</label>
                    <div class="controls">
                        <input type="text" id="site_url" name="site_url" class="input-xlarge" placeholder="Web Site Url" value="<?php echo set_value('site_url', isset($companyData['site_url']) ? _e($companyData['site_url']) : ''); ?>">
                        <?php if (isset($site_urlError)): ?>
                            <span class="help-block error"><?php echo $site_urlError; ?></span>
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
<script src="<?php echo base_url(); ?>js/jquery-uploader/vendor/jquery.ui.widget.js"></script>
<script src="<?php echo base_url(); ?>js/jquery-uploader/jquery.iframe-transport.js"></script>
<script src="<?php echo base_url(); ?>js/jquery-uploader/jquery.fileupload.js"></script>
<script src="<?php echo base_url(); ?>js/jquery-uploader/jquery.fileupload-fp.js"></script>
<script src="<?php echo base_url(); ?>js/jquery-uploader/jquery.fileupload-ui.js"></script>
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
            var companyId = <?php echo $id; ?>;
            if(confirm('Are you sure you want delete this file')){
                $.ajax({
                    url: "/admin/company/delete_company_photo",
                    type: "POST",
                    data:{
                        companyId: companyId
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
