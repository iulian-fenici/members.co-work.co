<form action="<?php echo $list_url;?>" method="get" id="filter_form" onsubmit="filter_list(); return false;">
    <input style="margin-top: 11px" type="text" class="medium" name="search_for" id="search_for" value="<?php echo $search_for;?>">
    <?php echo $drop_down;?>
    <a class="uibutton" href="javascript:filter_list()" style="margin-bottom: 10px;">Search</a>
    <?php if($search_for):?>
    <a class="uibutton" style="margin-bottom: 10px;" href="<?php echo $list_url;?>">Clear</a>
    <?php endif;?>
</form>