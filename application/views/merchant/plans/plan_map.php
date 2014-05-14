<link type="text/css" rel="stylesheet" media="print" href="/css/ziceadmin/zice.print-style.css"/>
<!--<link type="text/css" rel="stylesheet" media="print" href="/js/etimbo-jquery-print/css/print-preview.css"/>-->
<script type="text/javascript" src="/js/jquery.maphilight.js"></script>
<!--<script type="text/javascript" src="/js/etimbo-jquery-print/jquery.print-preview.js"></script>-->
<!--<script type="text/javascript" src="/js/printElement/jquery.printElement.js"></script>-->
<script type="text/javascript">
    $(document).ready(function () {
        var print_media = <?php echo !isset($print_media) || !$print_media? 0: 1;?>;
        $('#map').maphilight({
                fillColor: '008800',
                print_media: print_media,
                alwaysOn:true
        });
        hilightMapRegions();
        drawDeskNumbersScreen();
        $('map area').on('mouseover',function(event){
            var company = $(this).attr('company');
            //alert(company)
            var desk_number = $(this).attr('desk_number');
            var text = (typeof company ==='undefined' || company==''?'Free desk: '+desk_number : setTooltipText(this, $(this).attr('company'), $(this).attr('desk_number'), $(this).attr('desk_comment')))
            $(this).qtip({    
                content: {   
                    text: text
                },
                //show: { solo: true },
                show: {
                    event: event.type, // Use the same show event as the one that triggered the event handler
                    ready: true // Show the tooltip as soon as it's bound, vital so it shows up the first time you hover!
                },
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
            }, event);
                
        });
  
//        $('map area').on('click', function(){
//            openPopUp($(this).attr('company'), $(this).attr('id'));
//        });
    });
    function hilightMapRegions(){
            var company_colors = {};
            var colors = ['403bb6','19de83','bde536','306e68','6fbeda','98492e','d28a42','3a4a5b','3434a3','d3e57f','383fa1','d63f98','72e496','8f2e1d','df85c3','d33626','1a2297','4443c9','875fe4','434ce3','a96b3c','645a63','e3b460','935c76','9855e3','744132','81423c','326e66','e2dcad','e039e0','ddc964','3246a5','7d4372','c5bdb6','546f25','50ca4d','6a6577','8d7fcc','db7bc1','a174e1','9a6bc4','e68524','a51b4f','31c726','ce347c','db6c60','41beac','9f6445','8559a7','5fe235','5a9587','389525','435327','7a6bd5','875323','1c4776','64704e','2a299a','5695da','e4dcd5','325084','a06f32','ad9a6c','bb2dbf','a99c2b','b39f5a','431db1','782ec1','2b6b70','1f6865','dc829c','793c24','92d0a6','e6a4ba','be676f','d13428','445f2c','ddbe41','b7d194','40d8e4','8dcd7f','4260a3','4eda8c','dbd94a','afb198','379cb3','47c72b','5bbdd1','848ebc','31b6ae','2f5c95','9585dc','52bacf','c5afc2','28778c','a89641','74c422','8739c7','72a46e','48bd3d'];
            var company_id;
            var counter = 0;           
            $.each($('.desk') , function(i,val){
                company_id = $(val).attr('company_id');
                if(company_id != ''){
                    if(typeof(company_colors[company_id])=='undefined'){
                        company_colors[company_id] = colors[counter];
                        counter++;
                    }
                }
            });
            $.each($('.desk') , function(i,val){
                company_id = $(val).attr('company_id');
                if(company_id != ''){
                    $(val).data('maphilight', {fillColor:company_colors[company_id]}).trigger('alwaysOn.maphilight');
                }
                else
                    $(val).data('maphilight', {fillColor:'ffffff'}).trigger('alwaysOn.maphilight');
                
            });
    }
    
    function setTooltipText(el, company, desk_number, desk_comment){
        var email = getAttrValue(el, 'email', 'Email');
        var phone = getAttrValue(el, 'phone', 'Phone');
        var username = getAttrValue(el, 'username', 'User Name');
        var site_url = getAttrValue(el, 'site_url', 'Site Url');
        var template = 
            '<div>'+
            '<div class="company-tooltip">'+
            '<span><b>Company:</b> </span>' + $(el).attr('company') + '<br>'+
            email +
            phone + 
            site_url +
            username +
            '</div>'+
            '<span><b>Desk:</b> </span>' + desk_number + '<br>'+
            
            (typeof desk_comment ==='undefined' || desk_comment==null?'':'<span><b>Desk Comment:</b> </span>' + desk_comment + '<br>') +
            '</div>' ;
        return template;
    }
    
    function getAttrValue(el, attr_name, attr_label)
    {
        if($(el).attr(attr_name) != "")
            return '<span><b>'+attr_label+': </b></span>'+$(el).attr(attr_name)+'<br>';
        else
            return "";
    }
    function show_details(companyDeskId){
        if(companyDeskId !== undefined){
            $.fancybox({
                'transitionIn': 'none',
                'transitionOut': 'none',
                'type': 'ajax',
                'ajax' : {
                    'type': 'POST',
                    'data': {
                        'companyDeskId': companyDeskId
                    }
                },
                'href': '/merchant/plans/getDeskDetails/'
            });
        }
    }
</script>

<div class="row-fluid">
    <div class="span12 widget clearfix">
        <?php if (!isset($print_media) || !$print_media):?>
        <div class="widget-header">
            <span>
                <i class="icon-edit"></i>
                Desk Assignment
            </span>
        </div>
        <?php endif;?>
        <div class="widget-content">
            <?php if (!isset($print_media) || !$print_media):?>
            <a href="/merchant/plans/show_desks_print/<?php echo $mapId;?>" class="print-preview">Show desks for print</a>            
            <?php endif;?>
            <?php if (isset($print_button)):?>
                <?php echo $print_button;?>
            <?php endif;?>
                
            <div id="printArr">
            <img id="map" src="<?php echo $map; ?>"  alt="Planets" usemap="#planmap">
            <map name="planmap" id="planmap" style="-webkit-print-color-adjust:exact;">
                <?php echo isset($imageMap) && !empty($imageMap) ? $imageMap : ''; ?>
            </map> 
            </div>
        </div>
    </div>
</div>
