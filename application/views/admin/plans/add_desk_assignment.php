<script type="text/javascript">
    $(document).ready(function() {
        $('#company_id').on('change',function(){
            var companyId = $(this).find('option:selected').val();
            if(companyId ==''){
                $('.users-container').html('');
                return false;
            }
            $.ajax({
                url: '/admin/ajax/get_users_by_company_id_ajax/'+companyId,
                type: "POST",
                dataType: 'json',
                cache: false,
                async: true,
                success: function(ret){
                    if(ret.success != undefined)
                    {
                        $('.users-container').html(ret.html);
                    }
                }
            });
            $.ajax({
                url: '/admin/ajax/get_users_by_company_id_ajax/'+companyId,
                type: "POST",
                dataType: 'json',
                cache: false,
                async: true,
                success: function(ret){
                    if(ret.success != undefined)
                    {
                        $('.users-container').html(ret.html);
                        
                    }
                }
            });
            
        });
    });   

    function addDeskAssignment(url){
        if(confirm('Are you sure you want add this assignment?')){
            
            var company_id = $('#company_id option:selected').val();
            var user_id = $('#user_id option:selected').val();
            var desk_number = $('input[name="desk_number"]').val();
            var desk_comment = $('textarea[name="desk_comment"]').val();
            $.ajax({
                url: url,
                type: "POST",
                dataType: 'json',
                cache: false,
                async: true,
                data:{
                    save:'save',
                    company_id:company_id,
                    desk_number:desk_number,
                    desk_comment:desk_comment,
                    user_id:user_id
                },
                success: function(ret){
                    if(ret.success != undefined)
                    {
                        $('#'+ret.desk_id).attr('desk_number', ret.desk_number);
                        $('#'+ret.desk_id).attr('desk_comment',ret.desk_comment);
                        $('#'+ret.desk_id).attr('company_id', ret.company_id);
                        $('#'+ret.desk_id).attr('user_id', ret.user_id);
                        $('#'+ret.desk_id).attr('username', ret.username);
                        //$('#'+ret.desk_id).attr('cd_id', ret.cd_id);
                        $('#'+ret.desk_id).attr('company', ret.company);
                        hilightMapRegions();
                        location.reload(true);
                        $.fancybox.close();
                        
                    }else{
                        setError($('#editDeskErrorBlock'),ret.text,'error');
                    }
                }
            });
        }  
    }
</script>
<div class="row-fluid">
    <div class="span12">
        <div class="edit-desk-container">
            <div class="row-fluid">
                
                <?php $company_idError = form_error('company_id', ' ', ' '); ?>
                <div class="control-group">
                    <label class="control-label" for="company_id">Company</label>
                    <div class="controls">
                        <?php echo $companiesList; ?>
                        <?php if (isset($company_idError)): ?>
                            <span class="help-block error"><?php echo $company_idError; ?></span>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="users-container">

                </div>
                <div class="control-group">
                    <label class="control-label" for="desk_number">Desk Number</label>
                    <div class="controls">
                        <input type="text" id="desk_number" class="required" value="<?php echo isset($deskData['desk_number'])?$deskData['desk_number']:''; ?>" name="desk_number" />  
                    </div>
                </div>
                <div class="control-group ">
                    <label class="control-label" for="comment">Comment</label>
                    <div class="controls">
                        <textarea name="desk_comment"><?php echo isset($deskData['desk_comment'])?$deskData['desk_comment']:''; ?></textarea>
                    </div>
                </div>
                <div class="row-fluid error" id="editDeskErrorBlock">
                    
                </div>
                
                <div class="row-fluid">
                    <span class="btn btn-success pull-left" onclick="addDeskAssignment('<?php echo '/admin/plans/addDeskAssignment/' . $deskData['id']; ?>')">Save</span>
                </div>
            </div>
        </div>
    </div>
</div>