<?php if(isset($announcements)&&!empty($announcements)):?> 
    <?php foreach($announcements as $announcement):?>
        <div class="anonc">
            <h1><?php echo isset($announcement['title']) & !empty($announcement['title']) ? _e($announcement['title']) : ''; ?></h1>
            <div>
                <?php echo isset($announcement['description']) & !empty($announcement['description']) ? nl2br(_e($announcement['description'])) : ''; ?>
            </div>
            <div>
                <?php if($announcement['admin_announcement']==1):?>                
                    by admin 
                <?php elseif($announcement['admin_announcement']==0 && !empty($announcement['username'])): ?>
                    by <?php echo $announcement['username'];?>                   
                <?php endif; ?>
                <?php if (isset($announcement['date_from']) && !empty($announcement['date_from']) && $announcement['date_from'] != '0000-00-00 00:00:00'):?>
                    <div class="announces-date">
                        <?php echo date('d/m/Y',strtotime($announcement['date_from']));?>
                    </div>
                <?php endif;?>
            </div>
            <a href="/merchant/announcement/view/<?php echo $announcement['id']?>">read more</a>
        </div>
    <?php endforeach; ?>
<?php endif; ?>