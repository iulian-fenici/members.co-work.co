<div class="row-fluid">
    <div class="span12">
        <h3>Information</h3>
        <?php if(isset($informationsPages) && !empty($informationsPages)): ?>
                <?php foreach ($informationsPages as $key=>$val):?>
                <b>Location:</b> <?php echo $user_locs_names[$key] ?>
                <ul style="margin-bottom: 20px" class="clearfix">
                <?php foreach ( $val as $title => $page): ?>
                    <li class="information-block">
                        <a class="information-block-link" href="/merchant/information/page/<?php echo $page; ?>/<?php echo $key?>">
                            <?php echo $title; ?>
                        </a>
                    </li> 
                <?php endforeach?>
                </ul>
                <?php endforeach; ?>  
        <?php else: ?>
            <h2>No items found</h2>
        <?php endif; ?>
    </div>
</div>
