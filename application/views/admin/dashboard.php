<link rel='stylesheet' type='text/css' href='<?php echo base_url(); ?>css/jquery-fullcalendar/fullcalendar.css' />
<link rel='stylesheet' type='text/css' href='<?php echo base_url(); ?>css/jquery-fullcalendar/fullcalendar.print.css' media='print' />
<script type='text/javascript' src='<?php echo base_url(); ?>js/jquery-fullcalendar/fullcalendar.js'></script>

<link rel='stylesheet' type='text/css' href='<?php echo base_url(); ?>css/carousel/als.css' />
<script type='text/javascript' src='<?php echo base_url(); ?>js/carousel/jquery.alsEN-1.0.min.js'></script>
<script type='text/javascript' src='<?php echo base_url(); ?>js/dateformat/dateformat.js'></script>

<!--<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>css/jquery-ui/smoothness/jquery-ui-1.9.2.custom.min.css" />
<script type="text/javascript" src="<?php echo base_url(); ?>js/jquery-ui/jquery-ui-1.9.2.custom.min.js"></script>-->

<script type='text/javascript' src='<?php echo base_url(); ?>js/jquery.base64.min.js'></script>

<script type="text/javascript">
    $(document).ready(function() {
        $("#days-datepicker").datepicker({ 
            showButtonPanel: true,
            dateFormat: 'yy-mm-dd',
            showOptions: { 
                direction: "down" 
            },
            beforeShow: function(input, inst)
            {
                inst.dpDiv.css({marginTop: 30 + 'px', marginLeft: input.offsetWidth + 'px'});
            },
            onSelect:function(input, inst){
                $('input[name="filter-day"]').val(input);
                changeDaysFilter('calendar');
            }
        });
        $('#days-datepicker-button').click(function(){
            $("#days-datepicker").datepicker('show');
        }); 
        <?php if($this->input->get('date')):?>
            changeDaysFilter('date','<?php echo $this->input->get('date');?>');
        <?php else:?>
            loadDayFilter();  
        <?php endif;?>
//        $('.fc-agenda-slots').live('click', function (event){
//            addBooking();
//        });
    });
    
    
    function loadCalendar(date,dateString){
        $.ajax({
            url: "/admin/dashboard/get_calendar",
            type: "POST",
            dataType: 'json',
            cache: false,
            async: true,
            data:{
                date:date
            },
            success: function(ret){
                if(ret.success != undefined)
                {
                    initRooms(ret.rooms,dateString);
                }
            }
        });
    }
    function initRooms(rooms,dateString){
        replaceCarousel();
        $.each(rooms,function(i,val){
            $('ul.als-wrapper').append(
            '<li class="als-item">'+
                '<div id="calendar'+val.room_id+'" class="fullcalendar"></div>'+
                '</li>'
        );
            
            initCalendar('#calendar'+val.room_id,{'roomName':val.room_name + ' / '+ val.location_name}, val.bookings,dateString,val.room_id);
        });
        initCarousel();
    }
    function replaceCarousel(){
        var carousel = 
            '<div class="als-container" id="carousel">'+
            '<span class="als-prev"><img src="/img/main/prev.png" alt="prev" title="previous" /></span>'+
            '<div class="als-viewport">'+
            '<ul class="als-wrapper">'+
            '</ul> '+
            '</div>' +
            '<span class="als-next"><img src="/img/main/next.png" alt="next" title="next" /></span>'+
            '</div> ';
        $('.carousel-container').html(carousel);
    }
    function initCarousel(){
        $.fn.als('destroy');          
        $("#carousel").als({
            visible_items: 4,
            orientation: "horizontal"
        });        
    }
    function initCalendar(id, options, bookings, dateString,room_id){
        var dateStringSplitted = dateString.split("/");
        var date = new Date(dateStringSplitted[1]+"/"+dateStringSplitted[0]+"/"+dateStringSplitted[2]);
        var minTime = 7;
        var calendarHeight = 550;
        if(date.getDay() == 6 || date.getDay() == 0) 
        {
            minTime = 0;
            calendarHeight = 850;
        }
        var d = date.getDate();
        var m = date.getMonth();
        var y = date.getFullYear();
        $(id).fullCalendar({
            header: {
                left: '',
                center: '',
                right:''
            },
            roomName:options.roomName,
            defaultView: 'agendaDay',
            allDaySlot:false,
            slotMinutes:60,
            editable: false,
            disableResizing: true,
            height: calendarHeight,
            selectable:true,
            selectHelper: true,
            unselectAuto: true,
            minTime:minTime,
            select:function( startDate, endDate, allDay, jsEvent, view ){
                var nowDate = new Date();
                var startDate2 = addMinutes(startDate,45);
                if (startDate2 < nowDate)
                {
                    $(id).fullCalendar('unselect');
                    alert('Booking at this time is not possible, because the time already passed')
                    return false;
                }
                addBooking(startDate,endDate,room_id);
            },
            eventRender: function (event, element) {
                element.qtip({    
                    content: {   
                            text: setTooltipText(event)
//                            text: 'loading...',
//                            ajax: {
//                                    url: element.attr('data-url'), // URL to the local file
//                                    type: 'POST', // POST or GET
//                                    data: {} // Data to pass along with your request
//                            }       
                    },
                    show: { solo: true },
                    //hide: { when: 'inactive', delay: 3000 }, 
                    style: { 
                        width: 200,
                        padding: 5,
                        color: 'black',
        		classes: 'qtip-green',
                        textAlign: 'left',
                        border: {
                            width: 1,
                            radius: 3
                        },
                        tip: 'topLeft'
                    } 
                });
            },
            eventClick: function(event) {
                if (event.event_url) {
                    $.fancybox({
                        'transitionIn': 'none',
                        'transitionOut': 'none',
                        'type': 'ajax',
                        'href': event.event_url
                    });
                }
            },
            viewDisplay: function(view) {
                try {
                    setTimeline(view,id);
                } catch(err) {}
            },
             timeFormat: {
                agenda: 'h:mm tt{ - h:mm tt}'
             },
            events: bookings
        }).fullCalendar('gotoDate',y,m,d);
 
    }
    function loadDayFilter(){
        var date = $('input[name="filter-day"]').val();
        var dateString = $('.day-container').text();
        loadCalendar(date,dateString);
    }
    function setTooltipText(event){
        var template = 
            '<div>'+
                '<span><b>Start:</b> </span>' + ($.fullCalendar.formatDate(event.start, 'h:mm tt')) + '<br>'+
                '<span><b>End:</b> </span>' + ($.fullCalendar.formatDate(event.end, 'h:mm tt')) + '<br>'+
                '<span><b>Company:</b> </span>' + event.company + '<br>'+
                '<span><b>User:</b> </span>' + event.username + '<br>'+
                (typeof event.foruser ==='undefined' || event.foruser==null?'':'<span><b>For User:</b> </span>'+event.foruser+'<br>') +
                (typeof event.title ==='undefined' || event.title==''?'':'<span><b>Description:</b> </span>'+event.title)+
                
            '</div>' ;
        return template;
    }
    function changeDaysFilter(type,sDate){
        if(typeof(type)==='undefined') type = 'today';
        
        switch(type){
            case 'today':  
                var date = new Date();
                break;
            case 'next':  
                var todayDate = $('input[name="filter-day"]').val().split('-');
                var date = new Date(todayDate[0],(todayDate[1]-1),todayDate[2]);
                date.setDate(date.getDate()+1);
                break;
            case 'prev':  
                var todayDate = $('input[name="filter-day"]').val().split('-');
                var date = new Date(todayDate[0],(todayDate[1]-1),todayDate[2]);
                date.setDate(date.getDate()-1);
                break;
            case 'calendar':  
                var todayDate = $('input[name="filter-day"]').val().split('-');
                var date = new Date(todayDate[0],(todayDate[1]-1),todayDate[2]);
                break;
            case 'date':  
                var str = $.base64.decode(sDate)
                var date = new Date(str);
                break;
        }
        
        var d = date.getDate();
        var m = date.getMonth();
        var y = date.getFullYear();  

        $('.day-container').text(date.format('d/mm/yyyy'));
        $('input[name="filter-day"]').val(date.format('yyyy-mm-dd'));
        loadDayFilter();
    }
    
    function addBooking(startDate,endDate,room_id){
        var sDate = (startDate.getHours() < 10 ? '0' + startDate.getHours() : startDate.getHours()) +':'+ (startDate.getMinutes() < 10 ? '0' + startDate.getMinutes() : startDate.getMinutes());
        var eDate = (endDate.getHours() < 10 ? '0' + endDate.getHours() : endDate.getHours()) +':'+ (endDate.getMinutes() < 10 ? '0' + endDate.getMinutes() : endDate.getMinutes());
        var date = startDate.getFullYear()+'-'+("0" + (startDate.getMonth() + 1)).slice(-2)+'-'+("0" + startDate.getDate()).slice(-2);
        var duration = (endDate.getHours() == '0' ? 24 : endDate.getHours()) - startDate.getHours() ;
        duration = (duration < 10?'0'+duration:duration) + ':00';
        $.fancybox({
            'transitionIn': 'none',
            'transitionOut': 'none',
            'type': 'ajax',
            'ajax' : {
                'type': 'POST',
                'data': {
                    'jsStartHours':sDate,
                    'jsEndHours':eDate,
                    'jsDate':date,
                    'jsDuration':duration,
                    'jsRoomId':room_id
                }
            },
            'href': '/admin/booking/add_booking'
        });
    }
    function setTimeline(view,id) {
        var parentDiv = jQuery(id).find(".fc-agenda-slots:visible").parent();

        var timeline = parentDiv.children(".timeline");
        if (timeline.length == 0) { //if timeline isn't there, add it
            timeline = jQuery("<hr>").addClass("timeline");
            parentDiv.prepend(timeline);
        }

        var curTime = new Date('<?php echo date('M d, Y H:i:s');?>');

        var curCalView = view;

        if (curCalView.visStart < curTime && curCalView.visEnd > curTime) {
            timeline.show();
        } else {
           
            timeline.hide();
            return;
        }

        var curSeconds = ((curTime.getHours()-7) * 60 * 60) + (curTime.getMinutes() * 60) + curTime.getSeconds();
        var percentOfDay = curSeconds / 61200; //17 * 60 * 60 = 86400, # of seconds in a day
        var topLoc = Math.floor((parentDiv.height()) * percentOfDay);

        timeline.css("top", (topLoc) + "px");

        if (curCalView.name == "agendaWeek") { //week view, don't want the timeline to go the whole way across
            var dayCol = jQuery(".fc-today:visible");
            var left = dayCol.position().left + 1;
            var width = dayCol.width()-2;
            timeline.css({
                left: left + "px",
                width: width + "px"
            });
        }

    }
    function addMinutes(date, minutes) {
        return new Date(date.getTime() + minutes*60000);
    }
</script>

<div class="row-fluid">
    <div class="span12 widget clearfix">
        <div class="widget-header">
            <span>
                <i class="icon-list"></i>
                Bookings
            </span>
        </div>
        <div class="widget-content">
        <div class="row-fluid" >
            <?php if($this->input->get('date')):?>
                <a href="/admin/dashboard/month?date=<?php echo $this->input->get('date');?>" class="btn">Back to Month</a>
            <?php endif;?>
            <div class="days-filter-container admin">
                    <a href="/admin/dashboard/month" class="btn">Month</a>
                    <a href="javascript:void(0)" onclick="changeDaysFilter();" class="btn">Today</a>
                    <a href="javascript:void(0)" onclick="changeDaysFilter('prev');" class="btn"><i class="icon-arrow-left"></i></a>
                    <span class="day-container"><?php echo date('d/m/Y'); ?></span>
                    <input type="hidden" value="<?php echo date('Y-m-d'); ?>" name="filter-day"/>
                    <a href="javascript:void(0)" onclick="changeDaysFilter('next');" class="btn"><i class="icon-arrow-right"></i></a>
                    <input type="hidden" id="days-datepicker">
                    <a href="javascript:void(0)" id="days-datepicker-button" class="btn"><i class="icon-eye-open"></i></a>
<!--                    <a href="javascript:void(0)" onclick="addBooking();" class="uibutton confirm"><i class="icon-plus"></i>Add booking</a>-->
            </div>  
        </div>  
        <div class="row-fluid">
            <div class="carousel-container">
                <div class="als-container" id="carousel">

                    <span class="als-prev"><img src="/img/main/prev.png" alt="prev" title="previous" /></span>

                    <div class="als-viewport">

                        <ul class="als-wrapper">

                        </ul> 
                    </div> 
                    <span class="als-next"><img src="/img/main/next.png" alt="next" title="next" /></span> <!-- "next" button -->
                </div> 
            </div>
        </div>
    </div>
   </div>
</div>

