<script type="text/javascript">
    $(document).ready(function() {
        
    });
</script>

<div class="row-fluid">
    <div class="span12 widget clearfix">
        <div class="widget-header">
            <span>
                <i class="icon-eye-open"></i>
                Profile
            </span>
        </div>
        <div class="widget-content">
        <div class="row-fuid">
            <div class="span2 profile-card">
                <div>
                    <?php if(isset($userData->thumb_name) && !empty($userData->thumb_name) && file_exists($userData->thumb_rel_path . $userData->thumb_name)): ?>
                        <img src="<?php echo base_url() . $userData->thumb_rel_path . $userData->thumb_name; ?>" class="portfolio-img img-polaroid" alt="profile pic" >
                    <?php else: ?>
                        <img src="/img/main/no_profile_photo.png" class="portfolio-img img-polaroid" alt="profile pic" >
                    <?php endif; ?>
                </div>
            </div>
            <div class="span10 profile-card">
                <span class="card-name"><?php echo isset($userData->first_name) && !empty($userData->first_name) ? _e($userData->first_name) : ''; ?> <?php echo isset($userData->last_name) & !empty($userData->last_name) ? _e($userData->last_name) : ''; ?></span>
                <span class="card-email"><?php echo isset($userData->email) && !empty($userData->email) ? '<a href="mailto:'._e($userData->email).'">'._e($userData->email).'</a>' : ''; ?></span>
                <span class="card-phone"><?php echo isset($userData->phone) && !empty($userData->phone) ? _e($userData->phone) : ''; ?></span>
            </div>
            <div class="span10 profile-card">
                <?php if(isset($userData->stats['bookingSeconds']['h']) && !empty($userData->stats['bookingSeconds']['h']) || isset($userData->stats['bookingSeconds']['m']) & !empty($userData->stats['bookingSeconds']['m'])):?>
                    <span class="card-name"><b>Bookings time:</b> <?php echo isset($userData->stats['bookingSeconds']['h']) && !empty($userData->stats['bookingSeconds']['h']) ? _e($userData->stats['bookingSeconds']['h']).' h' : ''; ?> <?php echo isset($userData->stats['bookingSeconds']['m']) & !empty($userData->stats['bookingSeconds']['m']) ? _e($userData->stats['bookingSeconds']['m']).' m' : ''; ?></span>
                <?php endif; ?>
                <?php if(isset($userData->stats['bookingCount']) && !empty($userData->stats['bookingCount'])):?>    
                    <span class="card-email"><b>Bookings count:</b> <?php echo isset($userData->stats['bookingCount']) && !empty($userData->stats['bookingCount']) ? _e($userData->stats['bookingCount']) : ''; ?></span>
                <?php endif;?>
            </div>

        </div>
    </div>
</div> 
           
<?php if(isset($userData->bookings)&&!empty($userData->bookings)): ?>
    <div class="row-fuid">
        <div class="span12 widget clearfix">
            <div class="widget-header">
                <span>
                    <i class="icon-list"></i>
                    Recent Bookings
                </span>
            </div>
            <div class="widget-content">
            <?php $this->load->view('merchant/booking/bookings_list',array('bookings'=> $userData->bookings));?>
            </div>
        </div>
    </div>
<?php endif; ?>

</div>