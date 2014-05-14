<!--<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>css/fancybox/jquery.fancybox.css" />
<script type="text/javascript" src="<?php echo base_url(); ?>js/fancybox/jquery.fancybox.pack.js"></script>-->

<script type="text/javascript">
    $(document).ready(function() {
        $('#selectAll').live('click',function(){
            if($(this).prop("checked")){
                $('input.row-checkbox').attr('checked','checked');
            }else{
                $('input.row-checkbox').removeAttr('checked');
            }
        });
        $('#sendEmail').on('click',function(){
            if($('input.row-checkbox:checked').length > 0){
                $.fancybox({
                    'transitionIn': 'none',
                    'transitionOut': 'none',
                    'type': 'ajax',
                    'href': '/admin/user/send_email'
                });
            }else{
                alert('You must select at least one user');
            }
        });
    });

</script>
<div class="row-fluid">
    <div class="span12">
        <div style="float:left;margin-right: 50px">
            <a href="<?php echo '/admin/user/edit_user/0'; ?>" class="uibutton confirm large" style="display:inline-block">Add user</a>
        </div>    
        <?php if (isset($users) && !empty($users)): ?>
            <div style="float:left">
                <a href="javascript:void(0)" class="uibutton confirm large" id="sendEmail" style="display:inline-block">Send email</a>
            </div>
        <?php endif; ?>

        <div style="float: right">
            <?php $this->utility->draw_search_form('users_list', '/admin/user/users_list?'); ?>
        </div>  

    </div>
</div>
<br>
<div class="row-fluid">
    <div class="span12 widget clearfix">
        <div class="widget-header">
            <span>
                <i class="icon-list"></i>
                Users List
            </span>
        </div>
        <div class="widget-content">
            <table class="table table-bordered table-striped">
                <col span="1" style="width:20px"/>
                <col span="1"/>
                <col span="1"/>
                <col span="1"/>
                <col span="1"/>
                <col span="1"/>
                <col span="1"/>
                <thead>
                    <tr>
                        <th><input type="checkbox" id="selectAll"></th>
                        <?php
                        echo $this->utility->draw_table_header(
                                '/admin/user/users_list', array(
                            'user_name' => 'Name',
                            'user_email' => 'Email',
                            'company_name' => 'Company',
                                )
                        );
                        ?>
                        <th>Bookings Count</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (isset($users) && !empty($users)): ?>
                        <?php foreach ($users as $user): ?>                        
                            <tr>
                                <td>
                                    <input type="checkbox" class="row-checkbox" value="<?php echo $user['id']; ?>"> 
                                </td>
                                <td>
                                    <?php echo isset($user['name']) && !empty($user['name']) ? '<a href="/admin/user/view_user/' . $user['id'] . '">' . _e($user['name']) . '</a>' : ''; ?>
                                </td>
                                <td>
                                    <?php echo isset($user['email']) && !empty($user['email']) ? '<a href="/admin/user/view_user/' . $user['id'] . '">' . _e($user['email']) . '</a>' : ''; ?>
                                </td>
                                <td>
                                    <?php echo isset($user['company_name']) && !empty($user['company_name']) ? _e($user['company_name']) : '&nbsp;'; ?>
                                </td>
                                <td>
                                    <?php echo isset($user['bookings_count']) && !empty($user['bookings_count']) ? '<a href="/admin/booking/booking_list?&company_id=' . _e($user['company_id']) . '&user_id=' . _e($user['id']) . '&location_id=' . _e($user['booking_location_id']) . '">' . $user['bookings_count'] . '</a>' : '&nbsp;'; ?>
                                </td>
                                <td>
                                    <a href="<?php echo '/admin/user/edit_user/' . $user['id']; ?>" ><i class="icon-pencil"></i></a>
                                    <a href="<?php echo '/admin/user/delete_user/' . $user['id']; ?>" onclick="return confirm('Are you sure you want delete this employee ?')"><i class="icon-trash"></i></a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" style="text-align:center">
                                <h4>No users found</h4>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php if (isset($pagination) && !empty($pagination)): ?>
            <div class="pagination">
                <ul>
                    <?php echo $pagination; ?>
                </ul>
            </div>
        <?php endif; ?>
    </div>    
</div>

