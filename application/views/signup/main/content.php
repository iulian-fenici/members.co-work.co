<?php echo isset($header) ? $header : ''; ?>
<body>
    <div id="wrap">
        <div class="container">
            <?php echo isset($menu) ? $menu : ''; ?>
            <div class="content-container" style="margin-top: 60px;">
                <?php if (isset($sidebar)):?>
                    <div>
                        <?php echo isset($sidebar) ? $sidebar : ''; ?>
                    </div>
                <?php endif;?>
                

                    <?php 
                    if (isset($message) && !empty($message)):?>
                        <?php echo isset($message) ? $message : ''; ?>
                    <?php endif;?>
                    <?php echo isset($content) ? $content : ''; ?>
               
            </div>    
        </div>
        <div id="push"></div>
    </div> <!-- /container -->    
    <?php if(isset($footer)): ?>
        <div id="footer">
            <div class="container">
                <?php echo isset($footer) ? $footer : ''; ?>
            </div>
        </div>
    <?php endif; ?>
</body>
</html>
