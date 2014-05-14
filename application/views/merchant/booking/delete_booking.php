<script type="text/javascript">
    $(document).ready(function() {

    });   
    
    function deleteBooking(url,bookingId){
        if(confirm('Are you sure you want delete this booking?')){
            $.ajax({
                url: url,
                type: "POST",
                dataType: 'json',
                cache: false,
                async: true,
                success: function(ret){
                    if(ret.success != undefined)
                    {
                        loadDayFilter();
                        $('.booking-link-'+bookingId).parents('.booking-list-row').remove();
                        $.fancybox.close();
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
                <span>Cancel booking</span><br>
                <div class="row-fluid">
                    <span class="btn btn-danger" onclick="deleteBooking('<?php echo '/merchant/booking/cancel_booking/' . $bookingData->id; ?>',<?php echo $bookingData->id; ?>);">Cancel</span>
                </div>
            </div>
        </div>
    </div>
</div>
