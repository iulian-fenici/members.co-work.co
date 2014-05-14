<?php echo isset($header) ? $header : ''; ?>
<body id="body" onLoad="resizeH();" >
    <?php echo isset($menu) ? $menu : ''; ?>
    <div class="row-fluid" id="middle">
        <?php if(isset($sidebar)): ?>
            <?php echo isset($sidebar) ? $sidebar : ''; ?>
        <?php endif; ?>
       
        <div class="span10 content" id="content">
        <?php if(isset($message) && !empty($message)): ?>
            <?php echo isset($message) ? $message : ''; ?>
        <?php endif; ?>
            <?php echo isset($content) ? $content : ''; ?>
        </div>        
        <?php if(isset($footer)): ?>
            <?php echo isset($footer) ? $footer : ''; ?>
        <?php endif; ?>
    </div>  
</body>
</html>
