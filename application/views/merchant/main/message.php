<?php if ($this->messages->count() > 0): 
    ?>
    <?php $all = $this->messages->get(); ?>
    <?php foreach ($all as $type => $messages):?>
        <?php foreach ($messages as $message):?>
            <div class="alert alert-<?php echo $type;?>">
                <button type="button" class="close" data-dismiss="alert">×</button>
                 <?php echo $message;?>
            </div>
        <?php endforeach; ?>
    <?php endforeach; ?>
<?php endif; ?>

