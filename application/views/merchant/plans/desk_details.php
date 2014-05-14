<div class="row-fluid">
    <div class="span12">
        <span><strong>Desk Info</strong></span>
        <div style="margin-top: 10px">
            <div><?php echo $companyDeskData['desk_comment']; ?></div>
            <div><?php echo $companyDeskData['desk_number']; ?></div>
        </div>
        <span><strong>Company Info</strong></span>
        <div style="margin-top: 10px">
            <div><?php echo $companyDeskData['company_name']; ?></div>
            <div><?php echo $companyDeskData['company_description']; ?></div>
            <div><?php echo $companyDeskData['company_email']; ?></div>
            <div><?php echo $companyDeskData['company_site_url']; ?></div>
            <div><?php echo $companyDeskData['company_phone']; ?></div>
        </div>
        <?php if(isset($companyUsers) && !empty($companyUsers)): ?>
            <span><strong>Company Users</strong></span>
            <div style="margin-top: 10px">
                <?php foreach ($companyUsers as $user): ?>
                    <div><?php echo $user['username']; ?> (<?php echo $user['email']; ?>)</div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>
