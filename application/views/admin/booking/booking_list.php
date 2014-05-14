


<script type='text/javascript' src='<?php echo base_url(); ?>js/timepicker/jquery.timePicker.min.js'></script>
<link rel='stylesheet' type='text/css' href='<?php echo base_url(); ?>css/timepicker/timePicker.css' />

<script type="text/javascript">
    $(document).ready(function() {
        $("#from").datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat:'dd/mm/yy',
            onSelect:function(a,b){
                $('input[name="from"]').val(a);
            }
        });        
        $("#to").datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat:'dd/mm/yy',
            onSelect:function(a,b){
                $('input[name="to"]').val(a);
            }
        });
        $("#duration").timePicker({
            startTime: new Date(0, 0, 0, 0, 15, 0), // Using string. Can take string or Date object.
            endTime: new Date(0, 0, 0, 2, 30, 0), // Using Date object here.
            step: 15
        });
        $('#location_id').live('change',function(){
            var locationId = $(this).find('option:selected').val();
            $.ajax({
                url: '/admin/ajax/get_companies_by_location_id_ajax/'+locationId,
                type: "POST",
                dataType: 'json',
                cache: false,
                async: true,
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
                url: '/admin/ajax/get_rooms_by_location_id_ajax/'+locationId,
                type: "POST",
                dataType: 'json',
                cache: false,
                async: true,
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
                url: '/admin/ajax/get_for_users_by_company_id_ajax/'+companyId+'/'+userId,
                type: "POST",
                dataType: 'json',
                cache: false,
                async: true,
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
                url: '/admin/ajax/get_users_by_company_id_ajax/'+companyId,
                type: "POST",
                dataType: 'json',
                cache: false,
                async: true,
                success: function(ret){
                    if(ret.success != undefined)
                    {
                        $('.users-container').html(ret.html);
                       
                        
                    }
                }
            });
            $.ajax({
                url: '/admin/ajax/get_for_users_by_company_id_ajax/'+companyId,
                type: "POST",
                dataType: 'json',
                cache: false,
                async: true,
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
                url: '/admin/booking/add_booking/',
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
        <div style="float: left">
            <form action="/admin/booking/booking_list?" id="filter_form_extended" class="form form-inline" method="get">
                <div class="row-fluid">
                    <div class="span12"> 
                        <div class="locations-container" style="display: inline-block;float:left;margin-right: 4px">
                            <?php $this->load->view('admin/booking/add_booking/locations_list') ?>
                        </div>
                        <div class="rooms-container" style="display: inline-block;float:left;margin-right: 4px">
                            <?php $this->load->view('admin/booking/add_booking/rooms_list') ?>
                        </div>
                        <div class="copmanies-container" style="display: inline-block;float:left;margin-right: 4px">
                            <?php $this->load->view('admin/booking/add_booking/companies_list') ?>
                        </div>                
                        <div class="users-container" style="display: inline-block;float:left;margin-right: 4px">
                            <?php $this->load->view('admin/booking/add_booking/users_list') ?>
                        </div>
                        <div class="for-user-container" style="display: inline-block;float:left;margin-right: 4px">
                            <?php $this->load->view('admin/booking/add_booking/for_users_list') ?>
                        </div>
                    </div>
                </div>    
                <div class="row-fluid">
                    <div class="span12">                        
                        <div class="date-container" style="display: inline-block;float:left;margin-right: 4px">
                            <label>From</label><br>
                            <input type="text" id="from" placeholder="From"  value="<?php echo $this->input->get('from') ? $this->input->get('from') : ''; ?>" name="from" />  
                        </div>
                        <div class="time-container" style="display: inline-block;float:left;margin-right: 4px">
                            <label>To</label><br>
                            <input type="text" id="to" placeholder="To" value="<?php echo $this->input->get('to') ? $this->input->get('to') : ''; ?>" name="to" />  
                        </div>
                        <div class="lduration-container" style="display: inline-block;float:left;margin-right: 4px">
                            <label >Duration</label><br>
                            <input type="text" id="duration" placeholder="Duration" value="<?php echo $this->input->get('duration') ? $this->input->get('duration') : ''; ?>" name="duration" /> 
                        </div>
                        <div class="actions-container" style="display: inline-block;float:left;margin-right: 4px;margin-top: 25px;">

                            <a class="btn uibutton confirm" href="javascript:filter_list_extended()" style="margin-bottom:10px" >Search</a>
                            <?php if (!empty($_GET)): ?>
                                <a class="btn uibutton confirm" href="/admin/booking/booking_list?" style="margin-bottom:10px" >Clear</a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>                            
            </form>
        </div>   
    </div>
</div>

<div class="row-fluid">
    <div class="span12 widget clearfix">
        <div class="widget-header">
            <span>
                <i class="icon-list"></i>
                Booking List
            </span>
        </div>
        <div class="widget-content">
            <table class="table table-bordered table-striped">
                <col span="1"/>
                <col span="1"/>
                <col span="1"/>
                <col span="1"/>
                <col span="1"/>
                <col span="1"/>
                <col span="1"/>
                <col span="1"/>
                <thead>
                    <tr>
                        <?php
                        echo $this->utility->draw_table_header(
                                '/admin/booking/booking_list', array(
                            'company_name' => 'Company',
                            'location_name' => 'Location',
                            'room_name' => 'Room',
                            'from' => 'From',
                            'to' => 'To',
                            'duration' => "Duration",
                            'user_name' => 'User',
                            'for_user_id' => 'For User'
                                )
                        );
                        ?>

                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (isset($bookingsList) && !empty($bookingsList)): ?>
                        <?php foreach ($bookingsList as $booking): ?>                        
                            <tr>
                                <td>
                                    <?php echo isset($booking['company_name']) && !empty($booking['company_name']) ? _e($booking['company_name']) : '&nbsp;'; ?>
                                </td>
                                <td>
                                    <?php echo isset($booking['location_name']) && !empty($booking['location_name']) ? _e($booking['location_name']) : '&nbsp;'; ?>
                                </td>
                                <td>
                                    <?php echo isset($booking['room_name']) && !empty($booking['room_name']) ? _e($booking['room_name']) : '&nbsp;'; ?>
                                </td>
                                <td>
                                    <?php echo isset($booking['from']) && !empty($booking['from']) ? _e(date('g:i a - d/m/Y',strtotime($booking['from']))) : '&nbsp;'; ?>
                                </td>
                                <td>
                                    <?php echo isset($booking['to']) && !empty($booking['to']) ? _e(date('g:i a - d/m/Y',strtotime($booking['to']))) : '&nbsp;'; ?>
                                </td>
                                <td>
                                    <?php echo isset($booking['duration']) && !empty($booking['duration']) ? _e($booking['duration']) : '&nbsp;'; ?>
                                </td>
                                <td>
                                    <?php echo isset($booking['user_name']) && !empty($booking['user_name']) ? _e($booking['user_name']) : '&nbsp;'; ?>
                                </td>
                                <td>
                                    <?php echo isset($booking['foruser']) && !empty($booking['foruser']) ? _e($booking['foruser']) : '&nbsp;'; ?>
                                </td>
                                <td>
                                    <a href="<?php echo '/admin/booking/delete_booking/' . $booking['id']; ?>" onclick="return confirm('Are you sure you want delete this booking?')"><i class="icon-trash"></i></a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="9" style="text-align:center">
                                <h4>No bookings found</h4>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php if (isset($pagination) && !empty($pagination)): ?>
            <div class="pagination">
                <ul>
                    <?php echo $pagination; ?>
                </ul>
            </div>
        <?php endif; ?>
    </div>
</div>
