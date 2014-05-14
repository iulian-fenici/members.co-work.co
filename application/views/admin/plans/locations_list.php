<div class="row-fluid">
    <div class="span12">
        <?php if(isset($locationsArr)&&!empty($locationsArr)):?>
            <?php foreach($locationsArr as $location):?>
                <?php if(!empty($location['map_ids'])):?>
                    <div class="company-block"> 
                        <div>
                             <strong><?php echo $location['name']; ?></strong>
                        </div>
                        <?php if (!empty($location['maps']) && count($location['maps']>1)):?>
                            <?php foreach ($location['maps'] as $map):?>
                                <?php if(!empty($location['map_ids'])):?>
                                    <a class="company-block-link" href="/admin/plans/assign_desk/<?php echo $location['id']?>/<?php echo $map['id']; ?>">
                                <?php else: ?>
                                    <a class="company-block-link" href="javascript:void(0)" onclick="alert('This location has no map')">
                                <?php endif; ?> 
                                <?php echo isset($map['description'])&&!empty($map['description'])?$map['description']:$location['description'];?>

                    </a>
                            <?php endforeach;?>
                        <?php endif;?>        
               </div>
             <?php endif;?>
            <?php endforeach; ?>
        <?php else: ?>
            <h2>No items found</h2>
        <?php endif; ?>
    </div>
</div>
