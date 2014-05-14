<?php if(isset($usersList) && !empty($usersList)): ?>
    <div class="control-group users-container">
        <label class="control-label" for="user_id">Users</label>
        <div class="controls">
            <?php echo $usersList; ?>
            <?php if(isset($user_idError)): ?>
                <span class="help-block error"><?php echo $user_idError; ?></span>
            <?php endif; ?>
        </div>
    </div>
<?php endif; ?>