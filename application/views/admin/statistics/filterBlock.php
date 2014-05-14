<script type="text/javascript">
var locationsMessagesChart;    
$(document).ready(function() {
    $('#date_from').datepicker({ dateFormat: "yy-mm-dd" });
    $('#date_to').datepicker({ dateFormat: "yy-mm-dd" });
    
    $('#add_filters').click(function(){
       insertParam('date_interval',$('#date_from').val()+'|'+$('#date_to').val()); 
    });
    $('#clear_filters').click(function(){
       window.location = removeParameter(''+document.URL+'','date_interval'); 
       $('#date_from').val('');
       $('#date_to').val('');
    });
//    $('#date_from').live('change', function(){});
    
    
});
</script>
<div id="chart_filters">
    <input type="text" placeholder="Date from" class="filter_datepicker" value="<?php echo isset($dateFrom)&&$dateFrom?substr($dateFrom,0,-9):''?>" id="date_from"/>
    <input type="text" placeholder="Date to" class="filter_datepicker" value="<?php echo isset($dateTo)&&$dateTo?substr($dateTo,0,-9):''?>" id="date_to"/>
    <button id="add_filters" class="uibutton confirm filter_button"><img src="/img/main/refresh_icon.png" width="15" height="15"/></button>
    <?php if ((isset($dateFrom)&&$dateFrom) || (isset($dateTo)&&$dateTo)):?>
        <button id="clear_filters" class="uibutton confirm filter_button">Clear</button>
    <?php endif;?>
</div>