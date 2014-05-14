<script type="text/javascript">
    $(document).ready(function() {
       
    });   
    
    function deleteBooking(el,url){
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
                        if(el!=null || typeof(el)!=='undefined'){
                            $(el).remove();
                        }
                        loadDayFilter();
                    }
                }
            });
        }        
    }
    
</script>

<div class="row-fluid">
    <div class="span12"> 
        <table class="table table-bordered table-striped"style="width:100%;">
            <tbody>
                <?php if(isset($bookings) && !empty($bookings)): ?>    
       
                    <?php foreach ($bookings as $booking): ?>    
                    <tr class="booking-list-row">
                        <td class="booking-list-room-name">
                            <?php echo isset($booking['room_name']) && !empty($booking['room_name']) ? $booking['room_name'] .' / '.$booking['location_name'] : ''; ?>
                        </td>
                        <td class="booking-list-from">
                        <?php echo isset($booking['from']) && !empty($booking['from']) ? bookingListDateFormat($booking['from']) : ''; ?>
                        </td>
<!--                            <div class="booking-list-duration">
                        <?php //echo isset($booking['duration']) && !empty($booking['duration']) ? $booking['duration'] : '&nbsp;'; ?>
                        </div>-->
                        <td class="booking-list-user-name">
                        by <?php echo isset($booking['user_name']) && !empty($booking['user_name']) ? $booking['user_name'] : '&nbsp;'; ?>
                        </td>
                        <td class="booking-list-user-name">
                        <?php echo isset($booking['foruser']) && !empty($booking['foruser']) ? 'for '.$booking['foruser'] : '&nbsp;'; ?>
                        </td>
                        <td class="booking-list-cancel">
                            <?php if (checkBookingAvailableForCancel($booking['to'])):?>
                                <a class="btn btn-red booking-link-<?php echo $booking['id'];?>" onclick="deleteBooking($(this).parents('.booking-list-row'),'<?php echo '/merchant/booking/cancel_booking/'.$booking['id'];?>');" href="javascript:void(0)" >Cancel</a>
                                <?php else:?>
                                &nbsp;
                            <?php endif;?>
                        </td>      
                        
                    </tr>

                    <?php endforeach; ?> 
                       
                    <?php else: ?>
                        <tr>
                            <td colspan="6" style="text-align:center;">
                                <h4>No bookings found</h4>
                            </td>
                        </tr>
                    <?php endif; ?> 
                    
                        </tbody>
                    </table> 
        <?php if(isset($pagination) && !empty($pagination)): ?>
            <div class="pagination">
                <ul>
                    <?php echo $pagination; ?>
                </ul>
            </div>
        <?php endif; ?>
    </div>
</div>

