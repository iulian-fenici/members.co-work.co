<?php if(isset($forUsersList) && !empty($forUsersList)): ?>
    <div class="control-group users-container">
        <label class="control-label" for="for_user_id">For User</label>
        <div class="controls">
            <?php echo $forUsersList; ?>
            <?php if(isset($forUser_idError)): ?>
                <span class="help-block error"><?php echo $forUser_idError; ?></span>
            <?php endif; ?>
        </div>
    </div>
<?php endif; ?>