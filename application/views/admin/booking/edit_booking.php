<link rel="stylesheet" type="text/css" href="/css/jquery-ui/smoothness/jquery-ui-1.9.2.custom.min.css" />
<script type="text/javascript" src="/js/jquery-ui/jquery-ui-1.9.2.custom.min.js"></script>

<script type='text/javascript' src='<?php echo base_url(); ?>js/timepicker/jquery.timePicker.min.js'></script>
<link rel='stylesheet' type='text/css' href='<?php echo base_url(); ?>css/timepicker/timePicker.css' />

<script type="text/javascript">
    $(document).ready(function() {
       $("#date").datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat:'dd/mm/yy',
            minDate: 0 ,
            onSelect:function(a,b){
                $('input[name="date"]').val(a);
            }
        });
        
        $("#time").timePicker({step: 15, show24Hours: false});
        $("#duration").timePicker({
            startTime: new Date(0, 0, 0, 0, 15, 0), // Using string. Can take string or Date object.
            endTime: new Date(0, 0, 0, 2, 30, 0), // Using Date object here.
            step: 15
        });
    });   
    
    function deleteBooking(url){
        if(confirm('Are you sure you want delete this booking?')){
            $.ajax({
                url: url,
                type: "POST",
                dataType: 'json',
                cache: false,
                async: false,
                success: function(ret){
                    if(ret.success != undefined)
                    {
                        loadDayFilter();
                        $.fancybox.close();
                    }
                }
            });
        }        
    }
    function editBooking(url){
        if(confirm('Are you sure you want edit this booking?')){
            
            var date = $('input[name="date"]').val();
            var time = $('input[name="time"]').val();
            var duration = $('input[name="duration"]').val();
            var comment = $('textarea[name="comment"]').val();
            
            $.ajax({
                url: url,
                type: "POST",
                dataType: 'json',
                cache: false,
                async: false,
                data:{
                    date:date,
                    time:time,
                    duration:duration,
                    comment:comment
                },
                success: function(ret){
                    if(ret.success != undefined)
                    {
                        loadDayFilter();
                        $.fancybox.close();
                    }else{
                        setError($('#editBookingErrorBlock'),ret.text,'error');
                    }
                }
            });
        }  
    }
</script>
<div class="row-fluid">
    <div class="span12">
        <div class="edit-booking-container">
            <div class="row-fluid">
               <div class="control-group time-cont">
                    <label class="control-label" for="date">Date Start</label>
                    <div class="controls">
                        <input type="text" id="date" class="required" value="<?php echo isset($bookingData->from) ? date('d/m/Y', strtotime($bookingData->from)) : ''; ?>" name="date" />  
                    </div>
                </div>
                <div class="control-group time-cont">
                    <label class="control-label" for="time">Time Start</label>
                    <div class="controls">
                        <input type="text" id="time" class="required" value="<?php echo isset($bookingData->from)?date('g:i a', strtotime($bookingData->from)):''; ?>" name="time" />  
                    </div>
                </div>
                <div class="control-group time-cont">
                    <label class="control-label" for="duration">Duration</label>
                    <div class="controls">
                        <input type="text" id="duration" class="required" value="<?php echo isset($bookingData->duration)?$bookingData->duration:''; ?>" name="duration" /> 
                    </div>
                </div>                 
                <div class="control-group ">
                    <label class="control-label" for="comment">Comment</label>
                    <div class="controls">
                        <textarea name="comment"><?php echo isset($bookingData->description)?$bookingData->description:''; ?></textarea>
                    </div>
                </div>
                <div class="row-fluid error" id="editBookingErrorBlock">
                    
                </div>
                <div class="row-fluid">
                    <span class="btn btn-success pull-left" onclick="editBooking('<?php echo '/admin/ajax/update_booking_ajax/' . $bookingData->id; ?>')">Edit</span>
                    <span class="btn btn-danger pull-right" onclick="deleteBooking('<?php echo '/admin/ajax/delete_booking_ajax/' . $bookingData->id; ?>');">Delete</span>
                </div>
            </div>
        </div>
    </div>
</div>