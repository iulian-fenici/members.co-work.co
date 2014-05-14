<script type="text/javascript">
    $(document).ready(function() {
         
    });
    function sendEmail(type){
        var idsArr = [];
        var email_text = $('textarea[name="email_text"]').val();
        var email_subject = $('input[name="email_subject"]').val();
        
        $('input.row-checkbox:checked').each(function(i,val){
            idsArr.push($(val).val());
        })
        $.ajax({
            url: '/admin/ajax/send_email',
            type: "POST",
            dataType: 'json',
            cache: false,
            async: true,
            data:{
                idsArr:idsArr,
                email_text:email_text,
                email_subject:email_subject,
                type:type
            },
            success: function(ret){
                if(ret.success != undefined)
                {
                    $.fancybox.close();
//                    $('input.row-checkbox').removeAttr('checked');
                }else{
                    setError($('#sendEmailErrorBlock'),ret.text,'error');
                }
            }
        });
    }
</script>
<div class="row-fluid">
    <div class="span12">
        <div class="control-group ">
            <label class="control-label" for="email_subject">Email subject</label>
            <div class="controls">
                <input name="email_subject" type="text"/>
            </div>
        </div>
        <div class="control-group ">
            <label class="control-label" for="email_text">Email text</label>
            <div class="controls">
                <textarea name="email_text" ></textarea>
            </div>
        </div>
        <div class="row-fluid error" id="sendEmailErrorBlock">

        </div>
        <div class="row-fluid">
            <a href="javascript:void(0)" onclick="sendEmail('user_email');" class="btn btn-success">Send</a>
        </div>
    </div>
</div>