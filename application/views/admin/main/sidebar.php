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
        <li class="select"><a href="/admin/dashboard/index"><span class="ico gray shadow home" ></span><b>Dashboard</b></a></li>
        <li ><a href="/admin/location/locations_list" class="i-upload"><span class="ico gray shadow window"></span>Locations</a></li>
        <li ><a href="/admin/room/rooms_list" class="ico gray i-bookmark">Rooms</a></li>      
        <li><a href="/admin/company/companies_list" class="i-searches">Companies</a></li>
        <li><a href="/admin/user/users_list" class="ico gray shadow i-upload">Users</a></li>
        <li><a href="/admin/booking/booking_list" class="ico gray shadow i-upload">Bookings</a></li>
        <li><a href="/admin/statistics/statistic" class="ico gray shadow i-upload">Statistic</a></li>
        <li><a href="/admin/announcement/announcements_list" class="i-upload">Announcement</a></li>
        <li><a href="/admin/registration/send_invite" class="i-upload">Send Registration Invite</a></li>
        <li><a href="/admin/plans/locations" class="i-upload">Floor Plan Assigment</a></li>
        <li><a href="/admin/admin/admins_list" class="ico gray shadow i-upload">Admins</a></li>
    </ul>
</div>
