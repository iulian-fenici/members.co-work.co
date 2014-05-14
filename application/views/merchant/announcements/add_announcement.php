<link rel="stylesheet" type="text/css" href="/css/jquery-ui/smoothness/jquery-ui-1.9.2.custom.min.css" />
<script type="text/javascript" src="/js/jquery-ui/jquery-ui-1.9.2.custom.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $("#dateFrom").datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat:'dd/mm/yy'
        });
        $("#dateTill").datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat:'dd/mm/yy'
        });
        
        $('.select-all-users-link').on('click',function(){
            $.each($('input[name="users[]"]'),function(i,val){
                $(val).attr('checked','checked');
            });
        });
        $('.unselect-all-users-link').on('click',function(){
            $.each($('input[name="users[]"]'),function(i,val){
                $(val).removeAttr('checked');
            });
        });
        
         $('.select-all-companies-link').on('click',function(){
            $.each($('input[name="companies[]"]'),function(i,val){
                $(val).attr('checked','checked');
            });
        });
        $('.unselect-all-companies-link').on('click',function(){
            $.each($('input[name="companies[]"]'),function(i,val){
                $(val).removeAttr('checked');
            });
        });

    });
    
</script>
<div class="row">
    <div class="span12 widget clearfix">  
        <div class="widget-header">
            <span>
                <i class="icon-list"></i>
                Announcement
            </span>
        </div>
        <div class="widget-content">
            <form method="post" action="/merchant/announcement/<?php echo isset($id) && !empty($id) ? 'edit_announcement/' . $id : 'add_announcement'; ?>" class="form-horizontal">
                <input type="hidden" name="id" id="id" value="<?php echo isset($id) && $id ? $id : 0; ?>"/>  
                <?php $titleError = form_error('title', ' ', ' '); ?>
                <div class="control-group">
                    <label class="control-label" for="title">Subject</label>
                    <div class="controls">
                        <input type="text" class="span5" id="title" name="title" placeholder="Title" value="<?php echo set_value('title', isset($announcementData['title']) && !empty($announcementData['title']) ? $announcementData['title'] : ''); ?>">
                        <?php if (isset($titleError)): ?>
                            <span class="help-block error"><?php echo $titleError; ?></span>
                        <?php endif; ?>
                    </div>
                </div>

                <?php $descriptionError = form_error('description', ' ', ' '); ?>
                <div class="control-group">
                    <label class="control-label" for="description">Description</label>
                    <div class="controls">
                        <textarea id="description" class="span5" rows="5" name="description"  placeholder="Description" ><?php echo set_value('description', isset($announcementData['description']) && !empty($announcementData['description']) ? $announcementData['description'] : ''); ?></textarea>
                        <?php if (isset($descriptionError)): ?>
                            <span class="help-block error"><?php echo $descriptionError; ?></span>
                        <?php endif; ?>
                    </div>
                </div>

                <?php $dateFromError = form_error('dateFrom', ' ', ' '); ?>           
                <div class="control-group">
                    <label class="control-label" for="dateFrom">Date from</label>
                    <div class="controls">                    
                        <input type="text"  id="dateFrom" name="dateFrom" value="<?php echo set_value('date_from', isset($announcementData['date_from']) && !empty($announcementData['date_from']) && $announcementData['date_from'] != '0000-00-00 00:00:00' ? date('d/m/Y', strtotime($announcementData['date_from'])) : ''); ?>">
                        <?php if (isset($dateFromError)): ?>
                            <span class="help-block error"><?php echo $dateFromError; ?></span>
                        <?php endif; ?>
                    </div>
                </div>
                <?php $dateTillError = form_error('dateTill', ' ', ' '); ?>
                <div class="control-group">
                    <label class="control-label" for="dateTill">Date till</label>
                    <div class="controls">                    
                        <input type="text"  id="dateTill" name="dateTill" value="<?php echo set_value('date_till', isset($announcementData['date_till']) && !empty($announcementData['date_till']) && $announcementData['date_till'] != '0000-00-00 00:00:00' ? date('d/m/Y', strtotime($announcementData['date_till'])) : ''); ?>">
                        <?php if (isset($dateTillError)): ?>
                            <span class="help-block error"><?php echo $dateTillError; ?></span>
                        <?php endif; ?>
                    </div>
                </div>
                
                <?php $sendEmailError = form_error('sendEmail', ' ', ' '); ?>
                <div class="control-group">
                    <label class="control-label" for="sendEmail">Send email</label>
                    <div class="controls">        
                        <?php if(isset($id)):?>
                            <?php if(isset($announcementData['send_email']) && ($announcementData['send_email']==1 || $announcementData['send_email']==0)):?>
                                <input type="checkbox" disabled="disabled" name="sendEmail"   <?php echo isset($announcementData['send_email']) && $announcementData['send_email']==1 ? 'checked="checked"' : ''; ?> value="1">
                            <?php elseif(isset($announcementData['send_email']) && $announcementData['send_email']==2):?>
                                <span>Email sent</span>
                            <?php endif;?>
                        <?php else: ?>
                            <input type="checkbox" name="sendEmail"  <?php echo set_checkbox('sendEmail', '1'); ?> value="1">
                        <?php endif;?>
                        <?php if (isset($sendEmailError)): ?>
                            <span class="help-block error"><?php echo $sendEmailError; ?></span>
                        <?php endif; ?>
                    </div>
                </div>
                
               
                <?php if (isset($companiesArr) && !empty($companiesArr)): ?>
                    <div class="control-group">
                        <label class="control-label" for="companies">Companies</label>
                        <div class="controls">  
                            <span>
                                <a href="javascript:void(0)" class="select-all-companies-link" >Select all companies</a>
                                <a href="javascript:void(0)" style="margin-left:20px;" class="unselect-all-companies-link">Clear all companies</a>
                            </span>
                            <?php foreach ($companiesArr as $company): ?>
                                <span>
                                    <label class="checkbox">                                     
                                        <input type="checkbox" name="companies[]" <?php echo isset($company['checked']) && $company['checked'] ? 'checked="checked"' : ''; ?> value="<?php echo $company['id']; ?>"> <?php echo $company['name']; ?>
                                    </label>
                                </span>
                            <?php endforeach; ?>

                            <span class="help-block error"></span>
                        </div>
                    </div>
                <?php endif; ?>
                
                
                <?php if (isset($usersArr) && !empty($usersArr)): ?>
                    <div class="control-group">
                        
                        <label class="control-label" for="users">Users</label>
                        
                        <div class="controls">  
                            <span>
                                <a href="javascript:void(0)" class="select-all-users-link" >Select all users</a>
                                <a href="javascript:void(0)" style="margin-left:20px;" class="unselect-all-users-link">Clear all users</a>
                            </span>
                            <?php foreach ($usersArr as $user): ?>
                                <span>
                                    <label class="checkbox">
                                        <input type="checkbox" name="users[]" <?php echo isset($user['checked']) && $user['checked'] ? 'checked="checked"' : ''; ?> value="<?php echo $user['id']; ?>"> <?php echo $user['username']; ?>
                                    </label>
                                </span>
                            <?php endforeach; ?>

                            <span class="help-block error"></span>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="control-group">
                    <div class="controls">
                        <?php echo form_submit('save', 'Save', 'class="uibutton confirm"'); ?>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>


