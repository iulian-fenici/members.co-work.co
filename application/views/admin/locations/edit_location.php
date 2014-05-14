<div class="row-fluid">
    <div class="span12 widget clearfix">
        <div class="widget-header">
            <span>
                <i class="icon-edit"></i>
                Edit Location
            </span>
        </div>
        <div class="widget-content">
        <form method="post" action="/admin/location/edit_location/<?php echo isset($id)?$id:0; ?>" class="form-horizontal">
            <?php $nameError = form_error('name', ' ', ' '); ?>
            <div class="control-group">
                <label class="control-label" for="name">Name</label>
                <div class="controls">
                    <input type="text" id="name" name="name" class="input-xlarge" placeholder="Location Name" value="<?php echo set_value('name', isset($locationData['name']) ? _e($locationData['name']) : ''); ?>">
                    <?php if (isset($nameError)): ?>
                        <span class="help-block error"><?php echo $nameError; ?></span>
                    <?php endif; ?>
                </div>
            </div>
            <?php $descriptionError = form_error('description', ' ', ' '); ?>
            <div class="control-group">
                <label class="control-label" for="description">Description</label>
                <div class="controls">
                    <textarea name="description" id="description" class="input-xlarge" placeholder="Location Description"><?php echo set_value('description', isset($locationData['description']) ? _e($locationData['description']) : ''); ?></textarea>
                    <?php if (isset($descriptionError)): ?>
                        <span class="help-block error"><?php echo $descriptionError; ?></span>
                    <?php endif; ?>
                </div>
            </div>

            <div class="hidden-fields">

            </div> 
            <div class="control-group">
                <div class="controls">
                    <?php echo form_submit('save', 'Save', 'class="uibutton"'); ?>
                </div>
            </div>
            </form>
        </div>
    </div>   
</div>
