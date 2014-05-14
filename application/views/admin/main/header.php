<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 

    <title><?php echo $this->config->item('company_name').' - ';?>Backend</title>
    <meta name="description" content="<?php echo $this->config->item('company_name');?>"/>
    <meta name="keywords" content="<?php echo $this->config->item('company_name');?>"/>
    <meta name="author" content="London Brand Management Ltd."/>

   
 <link rel="shortcut icon" type="image/ico" href="images/favicon.ico"/> 

        <!-- CSS Stylesheet-->
        <link type="text/css" rel="stylesheet" href="/templates/ziceadmin/components/bootstrap/bootstrap.css" />
        <link type="text/css" rel="stylesheet" href="/templates/ziceadmin/components/bootstrap/bootstrap-responsive.css" />
        <link rel="stylesheet" href="<?php echo base_url();?>css/main-theme/style.css" type="text/css" media="screen" />
        <link type="text/css" rel="stylesheet" media="screen" href="/css/ziceadmin/zice.style.css"/>
        <link type="text/css" rel="stylesheet" media="print" href="/css/ziceadmin/zice.print-style.css"/>
        <link rel="stylesheet" href="<?php echo base_url();?>css/qtip/jquery.qtip.css" type="text/css" media="screen" />
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>css/fancybox/jquery.fancybox.css" />        

		
        <!--[if lte IE 8]><script language="javascript" type="text/javascript" src="/templates/ziceadmin/components/flot/excanvas.min.js"></script><![endif]-->  
		
        <script type="text/javascript" src="/js/ziceadmin/jquery.min.js"></script>
        <script type="text/javascript" src="/js/base/jquery.validate.min.js"></script>
        <script type="text/javascript" src="/templates/ziceadmin/components/ui/jquery.ui.min.js"></script> 
		<script type="text/javascript" src="/templates/ziceadmin/components/bootstrap/bootstrap.min.js"></script>
        <script type="text/javascript" src="/templates/ziceadmin/components/ui/timepicker.js"></script>
        <script type="text/javascript" src="/templates/ziceadmin/components/fullcalendar/fullcalendar.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>js/fancybox/jquery.fancybox.pack.js"></script>
        <script type="text/javascript" src="<?php echo base_url();?>/js/qtip/jquery.qtip.js"></script>
        <script type="text/javascript" src="<?php echo base_url();?>/js/script.js"></script>
        <script src="http://js3.n.ict/get_chat_script/livechat_2.js"></script>
 

   <script type="text/javascript">  	
       var contHeight = 0;
       $(document).ready(function(){
           contHeight = $("#content").height()+110;/* + padding-top*/
       });
       
		function getClientHeight() {
			return document.compatMode == 'CSS1Compat' && !window.opera ? document.documentElement.clientHeight : document.body.clientHeight;
		}
                
		function resizeH()
		{
            if(getClientHeight() > contHeight){
                $("#sidebar").height(function(i,val){
                return getClientHeight();
                });
            }
            else{ 
                $("#sidebar").css("height", contHeight);
            }
        }
	</script> 
  

  </head>