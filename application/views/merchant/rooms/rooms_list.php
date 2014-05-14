<?php if(isset($rooms)&&!empty($rooms)):?>
    <?php if(isset($bookingErrors)&&!empty($bookingErrors)):?>
        <div class="error">
            <?php echo implode('.',$bookingErrors);?>
        </div>
    <?php endif; ?>
    <?php foreach($rooms as $room):?>        
        <div class="room-item apps" data-room-id="<?php echo $room['room_id']; ?>" data-error="<?php echo (isset($room['bookingForFurtherTime'])&&empty($room['bookingForFurtherTime']))||isset($room['timeIntersect'])||!empty($bookingErrors)?'true':'false';?>">
            <div class="source-thumbnail">
                <?php if(isset($room['room_thumb_name']) && !empty($room['room_thumb_name']) && file_exists($room['room_thumb_abs_path'] . $room['room_thumb_name'])): ?>
                    <img src="<?php echo base_url() . $room['room_thumb_rel_path'] . $room['room_thumb_name']; ?>"/>
                <?php else: ?>
                    <img src="/img/main/no_picture.png"/>
                <?php endif; ?>
            </div>
            <div class="h4" title="<?php echo _e($room['room_name']); ?>"><?php echo _e($room['room_name']); ?></div>
            <div class="h4" title="<?php echo _e($room['location_name']); ?>"><?php echo _e($room['location_name']); ?></div>
            <?php if(isset($room['timeIntersect'])):?>
                <div class="error">
                    This room is already booked at this time
                </div>
            <?php endif;?>    
            <?php if(isset($room['nexAvailableBooking'])):?>
                <div class="available">
                    <label>Next available time:</label>
                    <?php echo '<span>' . date('M d',strtotime($room['nexAvailableBooking']['from'])) . '</span>'; ?><br/>
                    <?php echo 'from <span>' . date('g:i a',strtotime($room['nexAvailableBooking']['from'])) . '</span>'; ?>
                    <?php  echo 'to <span>' . date('g:i a',strtotime($room['nexAvailableBooking']['to'])) . '</span>'; ?>
                </div>
            <?php endif;?>   
            <?php if(isset($room['bookingForFurtherTime'])&&empty($room['bookingForFurtherTime'])):?>
                <div class="error">
                    Тime between booking must be over 2 hours
                </div>
            <?php endif;?>  
            <?php //foreach($room['bookings'] as $booking):?>
                <!--<div><?php //echo $booking['from'];?> - <?php //echo $booking['to'];?> : <?php //echo isset($booking['intersect'])&&$booking['intersect']?'true':'false';?></div>-->
            <?php //endforeach; ?>
        </div>
    <?php endforeach; ?>
    <div class="clearfix"></div>
<?php else:?>
    <h3>No rooms found</h3>
<?php endif; ?>