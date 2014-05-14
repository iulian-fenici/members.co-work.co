<div class="row-fluid">
    <div class="span12">
        <?php if(isset($companiesArr)&&!empty($companiesArr)):?>
            <?php foreach($companiesArr as $company):?>
                <a class="company-block-link" href="/merchant/company/company_view/<?php echo $company['id']; ?>">
                    <div class="company-block"> 
                        <div>
                             <strong><?php echo $company['name']; ?></strong>
                        </div>
                        <div>
                            <?php echo $company['description']; ?>
                        </div>
                        <?php if(isset($company['locs_names'])&&!empty($company['locs_names'])):?>
                        <div>
                            <strong>Locations:</strong> <?php echo $company['locs_names']; ?>
                        </div>
                        <?php endif?>
                    </div>
                </a>
            <?php endforeach; ?>
        <?php else: ?>
            <h2>No items found</h2>
        <?php endif; ?>
    </div>
</div>
