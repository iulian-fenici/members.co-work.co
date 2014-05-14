<script>
    $(document).ready(function(){
        var pageHref =window.location.pathname;
        $('.main_menu li a').each(function(){
            var linkHref = $(this).attr('href');
            
            if (pageHref==linkHref)
            {
                $('.main_menu li').removeClass('select');
                $(this).parent('li').addClass('select');
            }
        });              
    });

</script>

<div id="left_menu">
    <ul id="main_menu" class="main_menu">
     
    </ul>
</div>