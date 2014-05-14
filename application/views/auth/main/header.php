<!DOCTYPE html>
<html lang="en">
  <head>
        <meta charset="utf-8">
        <title><?php echo $this->config->item('company_name').' - ';?>Frontend</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!--[if lt IE 9]>
          <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->
        <script type="text/javascript" src="/js/base/jquery.min.js"></script>
        <script type="text/javascript" src="/templates/ziceadmin/components/bootstrap/bootstrap.min.js"></script>
        <script type="text/javascript" src="/templates/ziceadmin/components/ui/jquery.ui.min.js"></script>
        <script type="text/javascript" src="/templates/ziceadmin/components/form/form.js"></script>
        <script type="text/javascript" src="/js/ziceadmin/login_php.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>js/base/jquery.validate.min.js"></script>    
        <link type="text/css" rel="stylesheet" href="/templates/ziceadmin/components/bootstrap/bootstrap.css" />
        <link type="text/css" rel="stylesheet" href="/css/ziceadmin/zice.style.css"/>
        
        <style type="text/css">
        html {
            background-image: none;
        }
		body{
			background-position:0 0;
			}
        label.iPhoneCheckLabelOn span {
            padding-left:0px
        }
        #versionBar {
            background-color:#212121;
            position:fixed;
            width:100%;
            height:35px;
            bottom:0;
            left:0;
            text-align:center;
            line-height:35px;
            z-index:11;
            -webkit-box-shadow: black 0px 10px 10px -10px inset;
            -moz-box-shadow: black 0px 10px 10px -10px inset;
            box-shadow: black 0px 10px 10px -10px inset;
        }
        .copyright{
            text-align:center; font-size:10px; color:#CCC;
        }
        .copyright a{
            color:#A31F1A; text-decoration:none
        }    
        </style>
        </head>
        <body >