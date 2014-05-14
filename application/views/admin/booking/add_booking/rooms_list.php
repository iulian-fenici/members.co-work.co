<?php if(isset($roomsList) && !empty($roomsList)): ?>
    <div class="control-group locations-container">
        <label class="control-label" for="room_id">Rooms</label>
        <div class="controls">
            <?php echo $roomsList; ?>
            <?php if(isset($room_idError)): ?>
                <span class="help-block error"><?php echo $room_idError; ?></span>
            <?php endif; ?>
        </div>
    </div>
<?php endif; ?>