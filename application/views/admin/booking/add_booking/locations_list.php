<?php if(isset($locationsList) && !empty($locationsList)): ?>
    <div class="control-group locations-container">
        <label class="control-label" for="location_id">Locations</label>
        <div class="controls">
            <?php echo $locationsList; ?>
            <?php if(isset($location_idError)): ?>
                <span class="help-block error"><?php echo $location_idError; ?></span>
            <?php endif; ?>
        </div>
    </div>
<?php endif; ?>