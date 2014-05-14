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
        <li class="limenu"><a href="/merchant/dashboard/index"><span class="ico gray shadow home" ></span><b>Dashboard</b></a></li>
        <li class="limenu" ><a href="/merchant/booking/index" class="i-upload"><span class="ico gray shadow window"></span>Meeting Room Booking</a></li>
        <li class="limenu" ><a href="/merchant/profile/edit_profile" class="ico gray i-bookmark">Edit Profile</a></li>      
        <li class="limenu"><a href="/merchant/registration/send_invite" class="i-upload">Send Registration Invite</a></li>
        <li class="limenu"><a href="/merchant/plans/show_desks" class="i-upload">Floor Plans</a></li>
        <li class="limenu"><a href="/merchant/company/companies_list" class="i-upload">Companies</a></li>
        <li class="limenu"><a href="/merchant/announcement/my_announcements_list" class="i-upload">My Announcements</a></li>
        <li class="limenu"><a href="/merchant/announcement/announcements_list" class="i-upload">All Announcements</a></li>
        <li class="limenu"><a href="/merchant/information" class="i-upload">Information</a></li>
    </ul>
</div>