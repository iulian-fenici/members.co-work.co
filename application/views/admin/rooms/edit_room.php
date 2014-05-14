<link rel="stylesheet" href="<?php echo base_url(); ?>css/jquery-uploader/jquery.fileupload-ui.css" type="text/css" />

<div class="row-fluid">
    <div class="span12 widget clearfix">
        <div class="widget-header">
             <span>
                <i class="icon-edit"></i>
                Edit Room
            </span>
        </div>
        <div class="widget-content">
        <form method="post" action="/admin/room/edit_room/<?php echo isset($id)?$id:0; ?>" class="form-horizontal">
            <div class="control-group">
                <div class="controls">
                    <span class="uibutton large confirm fileinput-button">
                        <i class="icon-plus icon-white"></i>
                        <span>Add photo</span>
                        <input type="file" id="photo-uploader" name="files[]" data-url="/admin/upload/add_location_photo" >
                    </span>

                    <div class="preview">
                        <?php if (isset($roomData['thumb_name']) && !empty($roomData['thumb_name']) && file_exists($roomData['thumb_rel_path'] . $roomData['thumb_name'])): ?>
                            <div class="image-item">
                                <img src="<?php echo base_url() . $roomData['thumb_rel_path'] . $roomData['thumb_name']; ?>" class="img-polaroid" />
                                <div class="delete-button-container">
                                    <a href="javascript:void(0)" class="delete-file" title="Delete file"><i class="icon-trash"></i></a>
                                </div>
                            </div>
                        <?php endif; ?>   
                    </div>
                </div>
            </div>
            
            <?php $location_idError = form_error('location_id', ' ', ' '); ?>
            <div class="control-group">
                <label class="control-label" for="location_id">Locations</label>
                <div class="controls">
                   <?php echo $locationsList;?>
                    <?php if (isset($location_idError)): ?>
                        <span class="help-block error"><?php echo $location_idError; ?></span>
                    <?php endif; ?>
                </div>
            </div>
            <?php $nameError = form_error('name', ' ', ' '); ?>
            <div class="control-group">
                <label class="control-label" for="name">Name</label>
                <div class="controls">
                    <input type="text" id="name" name="name" class="input-xlarge" placeholder="Room Name" value="<?php echo set_value('name', isset($roomData['name']) ? _e($roomData['name']) : ''); ?>">
                    <?php if (isset($nameError)): ?>
                        <span class="help-block error"><?php echo $nameError; ?></span>
                    <?php endif; ?>
                </div>
            </div>
            <?php $descriptionError = form_error('description', ' ', ' '); ?>
            <div class="control-group">
                <label class="control-label" for="description">Description</label>
                <div class="controls">
                    <textarea name="description" id="description" class="input-xlarge" placeholder="Room Description"><?php echo set_value('description', isset($roomData['description']) ? _e($roomData['description']) : ''); ?></textarea>
                    <?php if (isset($descriptionError)): ?>
                        <span class="help-block error"><?php echo $descriptionError; ?></span>
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
            var roomId = <?php echo $id;?>;
            if(confirm('Are you sure you want delete this file')){
                $.ajax({
                    url: "/admin/room/delete_room_photo",
                    type: "POST",
                    data:{
                        roomId: roomId
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
