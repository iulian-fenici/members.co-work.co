
<form action="<?php echo $list_url;?>" method="get" id="filter_form_extended" onsubmit="filter_list_extended(); return false;">
    
    <?php foreach($options as $k=>$v):?>
        <input type="text" class="medium" name="<?php echo $k;?>" placeholder="<?php echo $v;?>" value="<?php echo $this->input->get($k)?_e($this->input->get($k,true)):''; ?>">
    <?php endforeach;?>
        
    <a class="uibutton" href="javascript:filter_list_extended()" style="margin-bottom: 10px;">Search</a>
    <?php if(!empty($_GET)):?>
        <a class="uibutton" style="margin-bottom: 10px;" href="<?php echo $list_url;?>">Clear</a>
    <?php endif;?>
</form>