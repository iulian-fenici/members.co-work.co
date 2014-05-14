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
                changeMonthFilter('calendar');
            }
        });
        $('#days-datepicker-button').click(function(){
            $("#days-datepicker").datepicker('show');
        }); 
        <?php if($this->input->get('date')):?>
            changeMonthFilter('date','<?php echo date('Y-m-d', strtotime($this->input->get('date')));?>');
        <?php else:?>
            loadDayFilter();   
        <?php endif;?>

    });
    
    function loadCalendar(date,dateString){
        $.ajax({
            url: "/admin/dashboard/get_month_calendar",
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
                    initCalendar('#month-calendar',ret.rooms,dateString);
                }
            }
        });
    }
    function changeMonthFilter(type,sDate){
        if(typeof(type)==='undefined') type = 'today';
        
        switch(type){
            case 'today':  
                var date = new Date();
                break;
            case 'next':  
                var todayDate = $('input[name="filter-day"]').val().split('-');
                var date = new Date(todayDate[0],(todayDate[1]-1),todayDate[2]);
                date.setMonth(date.getMonth()+1);
                break;
            case 'prev':  
                var todayDate = $('input[name="filter-day"]').val().split('-');
                var date = new Date(todayDate[0],(todayDate[1]-1),todayDate[2]);
                date.setMonth(date.getMonth()-1);
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
    function initCalendar(id, bookings, dateString)
    {   
        $('#month-calendar').html('');
        var dateStringSplitted = dateString.split("/");
        var date = new Date(dateStringSplitted[1]+"/"+dateStringSplitted[0]+"/"+dateStringSplitted[2]);

        var d = date.getDate();
        var m = date.getMonth();
        var y = date.getFullYear();
        $('#month-calendar').fullCalendar({
            header: {
                left: '',
                center: '',
                right:''
            },
            editable: true,
            eventRender: function (event, element) {
                element.qtip({    
                    content: {   
                        text: setTooltipText(event)
  
                    },
                    show: { solo: true },
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
            eventClick: function(calEvent, jsEvent, view) {
                dayClick(calEvent.start);
            },
            dayClick: function(date, allDay, jsEvent, view) {
                dayClick(date);
            },
            events: bookings
        }).fullCalendar('gotoDate',y,m,d);;
    }
    function loadDayFilter(){
        var date = $('input[name="filter-day"]').val();
        var dateString = $('.day-container').text();
        loadCalendar(date,dateString);
    }
    function setTooltipText(event){
        var template = 
            '<div>'+
                (typeof event.room_name ==='undefined' || event.room_name==null?'':'<span><b>Room name:</b> </span>'+event.room_name+'<br>') +
                (typeof event.location_name ==='undefined' || event.location_name==null?'':'<span><b>Location name:</b> </span>'+event.location_name+'<br>') +
                (typeof event.count ==='undefined' || event.count==''?'':'<span><b>Bookings count:</b> </span>'+event.count+'<br>')+
            '</div>' ;
        return template;
    }
    function dayClick(date){
        var str = $.base64.encode(date)
        window.location.href = '/admin/dashboard/index?date='+str;
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
                <div class="days-filter-container admin">
                    <a href="/admin/dashboard/index" class="btn">Day</a>
                    <a href="javascript:void(0)" onclick="changeMonthFilter();" class="btn">Today</a>
                    <a href="javascript:void(0)" onclick="changeMonthFilter('prev');" class="btn"><i class="icon-arrow-left"></i></a>
                    <span class="day-container"><?php echo date('d/m/Y'); ?></span>
                    <input type="hidden" value="<?php echo date('Y-m-d'); ?>" name="filter-day"/>
                    <a href="javascript:void(0)" onclick="changeMonthFilter('next');" class="btn"><i class="icon-arrow-right"></i></a>
                    <input type="hidden" id="days-datepicker">
                    <a href="javascript:void(0)" id="days-datepicker-button" class="btn"><i class="icon-eye-open"></i></a>
                </div>  
            </div>  
        <div class="row-fluid">
            <div class="span12">
                <div class="calendar-container" >
                    <div id="month-calendar"></div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

