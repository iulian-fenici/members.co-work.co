
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
        <li></li>
    </ul>
</div><!-- End Header -->