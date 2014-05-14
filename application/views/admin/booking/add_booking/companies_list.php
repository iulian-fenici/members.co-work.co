<?php if(isset($companiesList) && !empty($companiesList)): ?>
    <div class="control-group">
        <label class="control-label" for="company_id">Companies</label>
        <div class="controls">
            <?php echo $companiesList; ?>
            <?php if(isset($company_idError)): ?>
                <span class="help-block error"><?php echo $company_idError; ?></span>
            <?php endif; ?>
        </div>
    </div>
<?php endif; ?>