<link rel="stylesheet" type="text/css" href="/css/jquery-ui/smoothness/jquery-ui-1.9.2.custom.min.css" />
<script type="text/javascript" src="/js/jquery-ui/jquery-ui-1.9.2.custom.min.js"></script>

<script type='text/javascript' src='/js/timepicker/jquery.timePicker.min.js'></script>
<link rel='stylesheet' type='text/css' href='/css/timepicker/timePicker.css' />

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
            endTime: new Date(0, 0, 0, 2, 00, 0), // Using Date object here.
            step: 15
        });


        $('#addBookingSubmit').on('click',function(){
            var for_user_id = $('#for_user_id option:selected').val();
            var room_id = $('#room_id option:selected').val();
            
            var date = $('input[name="date"]').val();
            var time = $('input[name="time"]').val();
            var duration = $('input[name="duration"]').val();
            var comment = $('textarea[name="comment"]').val();
            
            duration = duration.replace(/[^0-9\:]/g, "");
            if(empty(duration)){
                alert('Duration format is wrong');
                $('input[name="duration"]').val('');
                return false;
            }
            
            loading('Processing...',1);
            $.ajax({
                url: '/merchant/booking/add_booking_popup/',
                type: "POST",
                dataType: 'json',
                cache: false,
                async: false,
                data:{
                    save:'save',
                    date:date,
                    time:time,
                    duration:duration,
                    comment:comment,
                    for_user_id:for_user_id,
                    room_id:'<?php echo $jsRoomId;?>'
                },
                success: function(ret){
                    if(ret.success != undefined)
                    {
                        loadDayFilter();
                        $.fancybox.close();
                        unloading();
                    }else{
                        setError($('#addBookingErrorBlock'),ret.text,'error');
                        unloading();
                    }
                }
            });
        });
    });  
</script>
<div class="row-fluid">
    <div class="span12">
        <div class="add-booking-container">
            <div class="row-fluid">
                <div class="control-group time-cont">
                    <label class="control-label" for="date">Date Start</label>
                    <div class="controls">
                        <input type="text" id="date" class="required" value="<?php echo isset($jsDate) ? $jsDate : ''; ?>" name="date" />  
                    </div>
                </div>
                <div class="control-group time-cont">
                    <label class="control-label" for="time">Time Start</label>
                    <div class="controls">
                        <input type="text" id="time" class="required" value="<?php echo isset($jsStartHours)?$jsStartHours:''; ?>" name="time" />  
                    </div>
                </div>
                <div class="control-group time-cont">
                    <label class="control-label" for="duration">Duration</label>
                    <div class="controls">
                        <input type="text" id="duration" class="required" value="<?php echo isset($jsDuration)?$jsDuration:''; ?>" name="duration" /> 
                    </div>
                </div> 
                <div class="for-user-container">
                    <?php echo $usersDD;?>
                </div>
                <div class="control-group ">
                    <label class="control-label" for="comment">Comment</label>
                    <div class="controls">
                        <textarea name="comment"><?php echo isset($bookingData->description)?$bookingData->description:''; ?></textarea>
                    </div>
                </div>
                <div class="row-fluid error" id="addBookingErrorBlock">
                    
                </div>
                
                <div class="row-fluid">
                    <span class="uibutton confirm" id="addBookingSubmit">Save</span>
                </div>
            </div>
        </div>
    </div>
</div>