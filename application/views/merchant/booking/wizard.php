
<script type='text/javascript' src='<?php echo base_url(); ?>js/timepicker/jquery.timePicker.min.js'></script>
<link rel='stylesheet' type='text/css' href='<?php echo base_url(); ?>css/timepicker/timePicker.css' />

<link rel='stylesheet' type='text/css' href='<?php echo base_url(); ?>css/wizard/wizard.css' />
<script type='text/javascript' src='/js/wizard/jquery.wizard.js'></script>


<script type="text/javascript">
    $(document).ready(function() {        
        loadDate();
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
            endTime: new Date(0, 0, 0, 2, 00, 0), // Using Date object here.
            step: 15
        });
        
        $("#booking-wizard").wizard({
            stepsWrapper: "#wrapped",
            submit: ".submit",
            enableSubmit:false,
            beforeForward: function( event, state ) {
                
                if(state.stepIndex == 2){
                    var duration = $('input[name="duration"]').val();
                    duration = duration.replace(/[^0-9\:]/g, "");
                    if(empty(duration)){
                        alert('Duration format is wrong');
                        $('input[name="duration"]').val('');
                        return false;
                    }
                }
                
                var inputs = $(this).wizard('state').step.find(':input');
                return !inputs.length || !!inputs.valid();
            },
            afterForward:function( event, state ) {
                if(state.stepIndex == 2){
                    loadRooms();
                }
            },
            afterBackward: function(event, state){
                 $('input[name="room_id"]').val('');
            },
        }).wizard('form').submit(function( event ) {
            //event.preventDefault();            

        }).validate({
            errorPlacement: function(error, element) { 
                if ( element.is(':radio') || element.is(':checkbox') ) {
                    error.insertBefore( element.next() );

                } else { 
                    error.insertAfter( element );
                }
            }
        });
        
        $('div.room-item').live('click',function(){
            $('div.room-item.selected').each(function(i,val){
                $(val).removeClass('selected');
            });
            $('input[name="room_id"]').val('');

            if($(this).hasClass('selected')){
                $(this).removeClass('selected');
                $('input[name="room_id"]').val('');
            }else{             
                if($(this).attr('data-error') == 'true')
                    return false;
                $(this).addClass('selected');
                $('input[name="room_id"]').val($(this).attr('data-room-id'));
            }
            
           
        });
    });
    function loadRooms(){
        var roomsContainer = $('div.rooms-container');
        var date = $('input[name="date"]').val();
        var time = $('input[name="time"]').val();
        var duration = $('input[name="duration"]').val();
        
        $.ajax({
            url: "/merchant/booking/get_booking_rooms",
            type: "POST",
            dataType: 'json',
            cache: false,
            async: false,
            data:{
                date:date,
                time:time,
                duration:duration                
            },
            success: function(ret){
                if(ret.success != undefined)
                {
                    $(roomsContainer).html(ret.html);
                }
            }
        });
    }
    function loadDate(){
        var date = new Date();
        var d = date.getDate();
        var m = date.getMonth();
        var y = date.getFullYear();
        $('input[name="date"]').val(y+'-'+(m+1)+'-'+d);
    }
</script>

<style>
    .wrap .title{
        background: none;
        padding-top: 2px;
    }
    
</style>    

<div class="row-fluid">
    
    <!-- Dashboard  widget -->
    <div class="widget  span12 clearfix">

        <div class="widget-header">
            <span><i class="icon-plus"></i> Add booking</span>
        </div><!-- End widget-header -->	

        <div class="widget-content">
            <div class="boxtitle">website status</div>
                <div class="row-fluid">
                    <div class="span12">
                        <div id="booking-wizard">
                            <form name="booking-wizard" action="/merchant/booking/add_booking" id="wrapped" method="POST">
                                <div class="step">
                                    <div class="multi-step four-steps numbered color-1" >
                                        <ol>
                                            <li class="current">
                                                <div class="wrap">
                                                    <p class="title">Select day</p>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="wrap">
                                                    <p class="title">Select time</p>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="wrap">
                                                    <p class="title">Select room</p>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="wrap">
                                                    <p class="title">Thanks</p>
                                                </div>
                                            </li>
                                        </ol>
                                    </div> 
                                    <div id="date">                        
                                        <input type="hidden" class="required" name="date" />  
                                    </div>                    
                                </div>

                                <div class="step">
                                <div class="multi-step four-steps numbered color-1">
                                    <ol>
                                        <li>
                                            <div class="wrap">
                                                <p class="title">Select day</p>
                                            </div>
                                        </li>
                                        <li class="current">
                                            <div class="wrap">
                                                <p class="title">Select time</p>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="wrap">
                                                <p class="title">Select room</p>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="wrap">
                                                <p class="title">Thanks</p>
                                            </div>
                                        </li>
                                    </ol>
                                </div>                    
                                    <div class="setcenter">
                                    <label>Time From</label>     
                                    <input type="text" id="time" class="required" name="time" />  
                                    <label>Duration</label>     
                                    <input type="text" id="duration" class="required" name="duration" />  
                                    </div>
                                </div>
                                <div class="step">   

                                <div class="multi-step four-steps numbered color-1">
                                    <ol>
                                        <li>
                                            <div class="wrap">
                                                <p class="title">Select day</p>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="wrap">
                                                <p class="title">Select time</p>
                                            </div>
                                        </li>
                                        <li class="current">
                                            <div class="wrap">
                                                <p class="title">Select room</p>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="wrap">
                                                <p class="title">Thanks</p>
                                            </div>
                                        </li>
                                    </ol>
                                </div>

                                <div class="rooms-container">

                                </div>
                                <input type="hidden" class="required" name="room_id" />  
                            </div>
                            <div class="submit step">

                            <div class="multi-step four-steps numbered color-1">
                                <ol>
                                    <li>
                                        <div class="wrap">
                                            <p class="title">Select day</p>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="wrap">
                                            <p class="title">Select time</p>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="wrap">
                                            <p class="title">Select room</p>
                                        </div>
                                    </li>
                                    <li class="current">
                                        <div class="wrap">
                                            <p class="title">Thanks</p>
                                        </div>
                                    </li>
                                </ol>
                            </div>
                                <div class="setcenter_com">
                                <label>Comment</label>                    
                                <textarea id="comment" name="comment"></textarea>  
                                <label>Book for </label>   
                                <?php echo $usersDD;?>
                                </div>
                            </div>
                            <div class="navigation">
                                <ul class="clearfix">
                                    <li><button type="button" name="backward" class="backward btn"><i class="icon-arrow-left"></i> Backward</button></li>
                                    <li><button type="button" name="forward" class="forward btn">Forward <i class="icon-arrow-right"></i> </button></li>
                                    <li><button type="submit" name="save" value="save" class="submit btn btn-success">Submit</button></li>
                                </ul>
                            </div>
                        </form>
                    </div>
                </div>
            </div><!-- row-fluid column-->
        </div><!--  end widget-content -->
    </div><!-- widget  span12 clearfix-->

</div>
