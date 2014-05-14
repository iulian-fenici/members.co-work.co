<script type="text/javascript">
    $(document).ready(function() {
        
    });
</script>
<div class="row-fluid">
    <div class="span12 clearfix widget">
        <div class="row-fuid">
            <div class="span2 profile-card">
                <div>
                    <?php if(isset($companyData['thumb_name']) && !empty($companyData['thumb_name']) && file_exists($companyData['thumb_rel_path'] . $companyData['thumb_name'])): ?>
                        <img src="<?php echo base_url() . $companyData['thumb_rel_path'] . $companyData['thumb_name']; ?>" class="portfolio-img img-polaroid" alt="company pic" >
                    <?php else: ?>
                        <img src="/img/main/no_profile_photo.png" class="portfolio-img img-polaroid" alt="company pic" >
                    <?php endif; ?>
                </div>
            </div>
            <div class="span10 profile-card">
                <span class="card-name"><?php echo isset($companyData['name']) && !empty($companyData['name']) ? _e($companyData['name']) : ''; ?></span>
                <span class="card-email"><?php echo isset($companyData['description']) && !empty($companyData['description']) ? _e($companyData['description']) : ''; ?></span>
                <span class="card-name"><?php echo isset($companyData['email']) && !empty($companyData['email']) ? '<a href="mailto:'._e($companyData['email']).'">'._e($companyData['email']).'</a>' : ''; ?></span>
                <span class="card-email"><?php echo isset($companyData['site_url']) && !empty($companyData['site_url']) ? '<a href="http://'._e($companyData['site_url']).'" target="_blank">'._e($companyData['site_url']).'</a>' : ''; ?></span>
                <span class="card-email"><?php echo isset($companyData['phone']) && !empty($companyData['phone']) ? _e($companyData['phone']) : ''; ?></span>

            </div>
            <?php if(isset($companyData['users']) && !empty($companyData['users'])): ?>
                <div class="span10 profile-card" style="margin-top: 10px">
                    <?php foreach ($companyData['users'] as $user): ?>
                        <div><?php echo $user['username']; ?> (<a href="mailto:<?php echo $user['email']; ?>"><?php echo $user['email']; ?></a>)</div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            <div class="span10 profile-card" style="margin-top: 10px">
                <?php if(isset($companyData['stats']['bookingSeconds']['h']) && !empty($companyData['stats']['bookingSeconds']['h']) || isset($companyData['stats']['bookingSeconds']['m']) & !empty($companyData['stats']['bookingSeconds']['m'])): ?>
                    <span class="card-name"><b>Bookings time:</b> <?php echo isset($companyData['stats']['bookingSeconds']['h']) && !empty($companyData['stats']['bookingSeconds']['h']) ? _e($companyData['stats']['bookingSeconds']['h']) . ' h' : ''; ?> <?php echo isset($companyData['stats']['bookingSeconds']['m']) & !empty($companyData['stats']['bookingSeconds']['m']) ? _e($companyData['stats']['bookingSeconds']['m']) . ' m' : ''; ?></span>
                <?php endif; ?>
                <?php if(isset($companyData['stats']['bookingCount']) && !empty($companyData['stats']['bookingCount'])): ?>    
                    <span class="card-email"><b>Bookings count:</b> <?php echo isset($companyData['stats']['bookingCount']) && !empty($companyData['stats']['bookingCount']) ? _e($companyData['stats']['bookingCount']) : ''; ?></span>
                <?php endif; ?>
                <?php if (isset($companyData['locs_names'])&&!empty($companyData['locs_names'])):?>
                    <div>
                        <span class="card-email"><b>Locations:</b> <?php echo _e($companyData['locs_names']) ?></span>
                    </div>
                <?php endif?>
            </div>
        </div>
    </div>
    <?php //if(isset($companyData['bookings']) && !empty($companyData['bookings'])): ?>
<!--        <div class="row-fuid">
            <div class="span12 widget clearfix">
                <div class="widget-header">
                    <span>
                        <i class="icon-list"></i>
                        Recent Bookings
                    </span>
                </div>
                <div class="widget-content">
                    <?php //$this->load->view('merchant/booking/bookings_list', array('bookings' => $companyData['bookings'])); ?>
                </div>
            </div>
        </div>-->
    <?php //endif; ?>
</div>

