<?php foreach($columns as $k=>$v):?>
<th class="header <?php echo $this->utility->get_sorted_state($k);?>" onclick="location.href='<?php echo $this->utility->build_filter_href($list_url,null,$k);?>'"><?php echo $v;?></th>
<?php endforeach;?>