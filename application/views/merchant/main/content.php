<?php echo isset($header) ? $header : ''; ?>
<body>        
    <?php echo isset($menu) ? $menu : ''; ?>

        <?php if(isset($sidebar)): ?>
            <?php echo isset($sidebar) ? $sidebar : ''; ?>
        <?php endif; ?>
       
  
        <div id="content" >
          <div class="inner">
              <div class="row-fluid">
                <div class="span12 clearfix">
                    <div class="logo"></div>
                </div>
              </div>
            <?php if(isset($message) && !empty($message)): ?>
                <?php echo isset($message) ? $message : ''; ?>
            <?php endif; ?>
              <?php echo isset($content) ? $content : ''; ?>
              <?php if(isset($footer)): ?>
                 <?php echo isset($footer) ? $footer : ''; ?>
              <?php endif; ?>