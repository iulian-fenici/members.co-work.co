<!--<link rel="stylesheet" type="text/css" href="/css/jquery-ui/smoothness/jquery-ui-1.9.2.custom.min.css" />
<script type="text/javascript" src="/js/jquery-ui/jquery-ui-1.9.2.custom.min.js"></script>-->


<script src="<?php echo base_url(); ?>js/highcharts/highcharts.js" type="text/javascript"></script>

<script type="text/javascript">
    var currentCriteria = 'bookingByUsers';
    var chart;
    $(document).ready(function(){
        var chart;
        $(document).ready(function(){
            bookingBy('bookingByUsers');
        });
        $('.nav-tabs li').live('click',function(){
            $('.nav-tabs li').removeClass('active');
            $(this).addClass('active');
        });
        
        $('.nav-pills li').live('click',function(){
            $('.nav-pills li').removeClass('active');
            $(this).addClass('active');
            bookingBy(currentCriteria);
        });
    });

    function bookingBy(bookingBy, el)
    {
        //$(this).parent().addClass('active');
        currentCriteria = bookingBy;
        var date_from = $('#date_from').val();
        var date_to =  $('#date_to').val();
        var resultType =  $('#resultType').val();
        var allowDecimals;
        $.ajax({
            url: '/admin/statistics/'+bookingBy,
            type: "POST",
            dataType: 'json',
            cache: false,
            async: true,
            data:{
                date_from: date_from,
                date_to: date_to,
                resultType: resultType
            },
            success: function(ret){
                console.log(ret);
                if(ret.success != undefined)
                {
                    if (ret.resultType)
                        $('#resultType').val(ret.resultType);
                    if (ret.resultType == 'time')
                        allowDecimals: true;
                    else
                       allowDecimals : false;
                    chart = new Highcharts.Chart({
                        chart: {
                            renderTo: 'container',
                            type: 'bar'
                        },
                        title: {
                            text: ret.title
                        },
                        subtitle: {
                            text: ''
                        },
                        xAxis: {
                            categories: ret.categories,
                            title: {
                                text: null
                            },
                            showEmpty:false
                        },
                        yAxis: {
                            min: 0,
                            title: {
                                text: ret.resultType == 'time'? 'Booking Time (hours)': 'Number of Bookings',
                                align: 'high'
                            },
                            labels: {
                                overflow: 'justify',
                               formatter: function () {
                                    if (ret.resultType == 'time')
                                        return convertTime(this.value);
                                    else
                                        if (Math.floor(this.value) == this.value)
                                            return this.value;
                                        else 
                                            return '';
                                }
                            },
                            allowDecimals: allowDecimals,
                            showEmpty:false
                        },
                        tooltip: {
                            formatter: function() {
                                if (ret.resultType == 'time')
                                    return '<b>'+ this.series.name +'</b>: '+ convertTime(this.y);
                                else
                                    return '<b>'+ this.series.name +'</b>: '+ this.y +' booking(s)';
                            }
                        },
                        plotOptions: {
                            bar: {
                                dataLabels: {
                                    enabled: true,
                                    formatter: function() {
                                        if (this.y === 0) {
                                            return null;
                                        } else {
                                            if (ret.resultType == 'time')
                                                return convertTime(this.y);
                                            else
                                                return this.y + ' booking(s)';
                                        }
                                    }
                                },
                                minPointLength:4
                            }
                        },
                        legend: {
                            layout: 'vertical',
                            align: 'right',
                            verticalAlign: 'top',
                            x: -100,
                            y: 100,
                            floating: true,
                            borderWidth: 1,
                            backgroundColor: '#FFFFFF',
                            shadow: true
                        },

                        credits: {
                            enabled: false
                        },
                        series: ret.series
                    });
                }
            }
        });
    }
    function convertTime(decTime)
    {
        var hour =  parseInt(Math.floor(decTime));        
        var min = parseInt(Math.round(60*(decTime-hour)));
        var minutes = min? min + " m":'';
        var hours = hour ? hour + " h ":'';
        
        if(min==0 && hour==0){
            return '0 h';
        }else{
            return hours + minutes;
        }
    }
    
    function result(resultType)
    {
        //insertParam('resultType',resultType);
        $('#resultType').val(resultType)
    }

</script>

<div class="row-fluid">
    <div class="span5">
        <ul class="nav nav-tabs">
            <li class="active">
                <a href="javascript:void(0);" onclick=" bookingBy('bookingByUsers', this);" class="" style="display:inline-block">Booking By Users</a>
            </li>
            <li>
                <a href="javascript:void(0);" onclick=" bookingBy('bookingByCompanies', this);" class="" style="display:inline-block">Booking By Companies</a>
            </li>
            <li>
                <a href="javascript:void(0);" onclick=" bookingBy('bookingByLocations', this);" class="" style="display:inline-block">Booking By Locations</a>
            </li>
        </ul>
    </div>
    <div class="span2">
        <ul class="nav nav-pills">
            <li id="hours" class="active">
                <a href="javascript:void(0);"  onclick="result('time');return false;">Hours</a>
            </li>
            <li id="bookings">
                <a href="javascript:void(0);" onclick="result('bookings');return false;">Bookings</a>
            </li>
        </ul>
    </div>
    <div class="span5">
        <?php echo $this->load->view('admin/statistics/filterBlock'); ?>
    </div>
</div>
<div class="row-fluid">
    <input type="hidden" name="resultType" value="<?php echo isset($resultType)&&$resultType?$resultType:'';?>" id="resultType">
    <div class="span11" style="margin-top: 30px">
        <div id="container" style="width: 100%; height: 400px"></div>
    </div>
</div>
