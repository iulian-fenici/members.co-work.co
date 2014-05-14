<script type="text/javascript">
    $(document).ready(function() {
        
    });
</script>
<div class="row-fluid">
    <div class="span12 clearfix widget">
        <div class="row-fuid">
            <div class="span2 profile-card">
                <div>
                    <?php if (isset($companyData->thumb_name) && !empty($companyData->thumb_name) && file_exists($companyData->thumb_rel_path . $companyData->thumb_name)): ?>
                        <img src="<?php echo base_url() . $companyData->thumb_rel_path . $companyData->thumb_name; ?>" class="portfolio-img img-polaroid" alt="company pic" >
                    <?php else: ?>
                        <img src="/img/main/no_profile_photo.png" class="portfolio-img img-polaroid" alt="company pic" >
                    <?php endif; ?>
                </div>
            </div>
            <div class="span10 profile-card">
                <span class="card-name"><?php echo isset($companyData->name) && !empty($companyData->name) ? _e($companyData->name) : ''; ?></span>
                <span class="card-email"><?php echo isset($companyData->description) && !empty($companyData->description) ? _e($companyData->description) : ''; ?></span>
            </div>
            <div class="span10 profile-card">
                <?php if (isset($companyData->stats['bookingSeconds']['h']) && !empty($companyData->stats['bookingSeconds']['h']) || isset($companyData->stats['bookingSeconds']['m']) & !empty($companyData->stats['bookingSeconds']['m'])): ?>
                    <span class="card-name"><b>Bookings time:</b> <?php echo isset($companyData->stats['bookingSeconds']['h']) && !empty($companyData->stats['bookingSeconds']['h']) ? _e($companyData->stats['bookingSeconds']['h']) . ' h' : ''; ?> <?php echo isset($companyData->stats['bookingSeconds']['m']) & !empty($companyData->stats['bookingSeconds']['m']) ? _e($companyData->stats['bookingSeconds']['m']) . ' m' : ''; ?></span>
                <?php endif; ?>
                <?php if (isset($companyData->stats['bookingCount']) && !empty($companyData->stats['bookingCount'])): ?>    
                    <span class="card-email"><b>Bookings count:</b> <?php echo isset($companyData->stats['bookingCount']) && !empty($companyData->stats['bookingCount']) ? _e($companyData->stats['bookingCount']) : ''; ?></span>
                <?php endif; ?>
                    
                <?php if (isset($companyData->site_url) && !empty($companyData->site_url)): ?>    
                    <span class="card-email"><b>Site Url:</b> <a href="http://<?php echo $companyData->site_url; ?>" target="_blank"><?php echo $companyData->site_url; ?></a></span>
                <?php endif; ?>
                <?php if (isset($companyData->email) && !empty($companyData->email)): ?>    
                   <span class="card-email"><b>Email:</b> <a href="mailto:<?php echo $companyData->email; ?>"><?php echo $companyData->email; ?></a></span>
               <?php endif; ?>                   
                <?php if (isset($companyData->phone) && !empty($companyData->phone)): ?>    
                    <span class="card-email"><b>Phone:</b> <?php echo $companyData->phone; ?></span>
               <?php endif; ?>

            </div>
        </div>
    </div>
    <?php if (isset($companyData->bookings) && !empty($companyData->bookings)): ?>
        <div class="row-fuid">
            <div class="span12 widget clearfix">
                <div class="widget-header">
                    <span>
                        <i class="icon-list"></i>
                        Recent Bookings
                    </span>
                </div>
                <div class="widget-content">
                    <?php $this->load->view('merchant/booking/bookings_list', array('bookings' => $companyData->bookings)); ?>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>
</div>
