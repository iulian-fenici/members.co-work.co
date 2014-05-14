<link rel="stylesheet" type="text/css" href="/css/jquery-ui/smoothness/jquery-ui-1.9.2.custom.min.css" />
<script type="text/javascript" src="/js/jquery-ui/jquery-ui-1.9.2.custom.min.js"></script>

<script type='text/javascript' src='<?php echo base_url(); ?>js/timepicker/jquery.timePicker.min.js'></script>
<link rel='stylesheet' type='text/css' href='<?php echo base_url(); ?>css/timepicker/timePicker.css' />

<script type="text/javascript">
    $(document).ready(function() {
       $("#date").datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat:'yy-mm-dd',
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
        
        $("#duration").on('keyup',function(){
            alert('dd');
        });
        
        $('#location_id').live('change',function(){
            var locationId = $(this).find('option:selected').val();
            $.ajax({
                url: '/merchant/ajax/get_companies_by_location_id_ajax/'+locationId,
                type: "POST",
                dataType: 'json',
                cache: false,
                async: false,
                success: function(ret){
                    if(ret.success != undefined)
                    {
                        $('.copmanies-container').html(ret.html);
                        $('.users-container').html('');
                        $('.for-user-container').html('');
                    }
                }
            });
            $.ajax({
                url: '/merchant/ajax/get_rooms_by_location_id_ajax/'+locationId,
                type: "POST",
                dataType: 'json',
                cache: false,
                async: false,
                success: function(ret){
                    if(ret.success != undefined)
                    {
                        $('.rooms-container').html(ret.html);
                    }
                }
            });
            
        });
        $('#user_id').live('change',function(){
            var companyId = $('#company_id').find('option:selected').val();
            var userId = $(this).find('option:selected').val();
            $.ajax({
                url: '/merchant/ajax/get_for_users_by_company_id_ajax/'+companyId+'/'+userId,
                type: "POST",
                dataType: 'json',
                cache: false,
                async: false,
                success: function(ret){
                    if(ret.success != undefined)
                    {
                        $('.for-user-container').html(ret.html);
                        
                    }
                }
            });
        });
        $('#company_id').live('change',function(){
            var companyId = $(this).find('option:selected').val();
            $.ajax({
                url: '/merchant/ajax/get_users_by_company_id_ajax/'+companyId,
                type: "POST",
                dataType: 'json',
                cache: false,
                async: false,
                success: function(ret){
                    if(ret.success != undefined)
                    {
                        $('.users-container').html(ret.html);
                       
                        
                    }
                }
            });
            $.ajax({
                url: '/merchant/ajax/get_for_users_by_company_id_ajax/'+companyId,
                type: "POST",
                dataType: 'json',
                cache: false,
                async: false,
                success: function(ret){
                    if(ret.success != undefined)
                    {
                        $('.for-user-container').html(ret.html);
                        
                    }
                }
            });
            
        });
        $('#addBookingSubmit').live('click',function(){
            
            var location_id = $('#location_id option:selected').val();
            var company_id = $('#company_id option:selected').val();
            var user_id = $('#user_id option:selected').val();
            var for_user_id = $('#for_user_id option:selected').val();
            var room_id = $('#room_id option:selected').val();
            
            var date = $('input[name="date"]').val();
            var time = $('input[name="time"]').val();
            var duration = $('input[name="duration"]').val();
            var comment = $('textarea[name="comment"]').val();
            
            $.ajax({
                url: '/merchant/booking/add_booking1/',
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
                    user_id:user_id,
                    location_id:location_id,
                    company_id:company_id,
                    for_user_id:for_user_id,
                    room_id:room_id
                    
                },
                success: function(ret){
                    if(ret.success != undefined)
                    {
                        loadDayFilter();
                        $.fancybox.close();
                    }else{
                        setError($('#addBookingErrorBlock'),ret.text,'error');
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
                <div class="locations-container">
                    <?php $this->load->view('merchant/booking/locations_list')?>
                </div>
                <div class="rooms-container">
                    
                </div>
                <div class="copmanies-container">
                    
                </div>                
                <div class="users-container">
                    
                </div>
                
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
                <div class="for-user-container">
                    
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