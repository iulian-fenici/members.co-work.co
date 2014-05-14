
 <div id="header">
    <ul id="head-logo" class="pull-left"> 
        <li>
        <a href="/">
        <?php if(file_exists(FCPATH . 'img/logo/logo.png')): ?>                
            <img src="/img/logo/logo.png" alt="logo" >
        <?php else: ?>  
            <span id="logo_text"><?php echo $this->config->item('company_name');?></span>
        <?php endif; ?>                     
        </a>
        </li>
   </ul>
     
    <ul id="account_info" class="pull-right"> 
        <li class="setting">
            Welcome, <b class="red"><?php echo $this->session->userdata('username');?></b>
            <ul class="subnav">
                <li><a href="/merchant/profile/view_profile">View Profile</a></li>
                <li><a href="/merchant/profile/edit_profile">Edit Profile</a></li>
                <br class="clearfix"/>
            </ul>
        </li>
        <li class="logout" title="Logout"><a href="/auth/logout">Logout</a></li> 
    </ul>
</div><!-- End Header -->