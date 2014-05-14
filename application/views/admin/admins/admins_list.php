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
    });

</script>
<div class="row-fluid">
    <div class="span12">
        <div style="float:left;margin-right: 50px">
            <a href="<?php echo '/admin/admin/edit_admin/'; ?>" class="uibutton confirm large" style="display:inline-block">Add admin</a>
        </div>    
        <div style="float: right">
            <?php $this->utility->draw_search_form('admins_list', '/admin/admin/admins_list?'); ?>
        </div>  

    </div>
</div>
<br>
<div class="row-fluid">
    <div class="span12 widget clearfix">
        <div class="widget-header">
            <span>
                <i class="icon-list"></i>
                Admins List
            </span>
        </div>
        <div class="widget-content">
            <table class="table table-bordered table-striped">
                <col span="1" style="width:20px"/>
                <col span="1"/>
                <col span="1"/>
                <col span="1"/>
                <col span="1"/>
                <thead>
                    <tr>
                        <th><input type="checkbox" id="selectAll"></th>
                        <?php
                        echo $this->utility->draw_table_header(
                                '/admin/admin/admins_list', array(
                            'user_name' => 'Name',
                            'user_email' => 'Email',
                                )
                        );
                        ?>
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
                                    <a href="<?php echo '/admin/admin/edit_admin/' . $user['id']; ?>" ><i class="icon-pencil"></i></a>
                                    <a href="<?php echo '/admin/admin/delete_admin/' . $user['id']; ?>" onclick="return confirm('Are you sure you want delete this admin ?')"><i class="icon-trash"></i></a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" style="text-align:center">
                                <h4>No admins found</h4>
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

