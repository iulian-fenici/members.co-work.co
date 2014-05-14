<div class="row-fluid">
    <div class="span12">
        <div style="float:left">
            <a href="<?php echo '/admin/company/edit_company/0'; ?>" class="uibutton confirm" style="display:inline-block"><i class="icon-plus"></i>Add Company</a>
        </div>
        <div style="float: right">
            <?php $this->utility->draw_search_form('companies_list', '/admin/company/companies_list?'); ?>
        </div>  
    </div>
</div>
<br>
<div class="row-fluid">
    <div class="span12 widget clearfix">
        <div class="widget-header">
             <span>
                <i class="icon-list"></i>
                Companies
            </span>
        </div>
        <div class="widget-content">
            <table class="table table-striped">
                <col span="1"/>
                <col span="1"/>
                <col span="1"/>
                <col span="1"/>
                <col span="1"/>
                <thead>
                    <tr>
                        <th>Icon</th>
                        <?php
                        echo $this->utility->draw_table_header(
                                '/admin/company/companies_list', array(
                            'company_name' => 'Name',
                            'company_description' => 'Description',
                            'company_location' => 'Locations',
                                )
                        );
                        ?>
                        <th>Users Count</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (isset($companies) && !empty($companies)): ?>
                        <?php foreach ($companies as $company): ?>                        
                            <tr>
                                <td>                                
                                    <?php if (isset($company['thumb_name']) && !empty($company['thumb_name']) && file_exists($company['thumb_rel_path'] . $company['thumb_name'])): ?>
                                        <img src="<?php echo base_url() . $company['thumb_rel_path'] . $company['thumb_name']; ?>" class="img-polaroid user-department-list" alt="Company icon" >
                                    <?php else: ?>
                                        <img src="/img/main/no_picture.png" class="department-icon user-department-list" alt="Company pic" >
                                    <?php endif; ?>                                    
                                </td>
                                <td>
                                    <?php echo isset($company['name']) && !empty($company['name']) ? '<a href="/admin/company/view_company/' . $company['id'] . '">' . _e($company['name']) . '</a>' : ''; ?>
                                </td>
                                <td>
                                    <?php echo isset($company['description']) && !empty($company['description']) ? '<a href="/admin/company/view_company/' . $company['id'] . '">' . _e($company['description']) . '</a>' : ''; ?>
                                </td>
                                <td>
                                    <?php echo isset($company['locs_names']) && !empty($company['locs_names']) ? _e($company['locs_names']) : ''; ?>
                                </td>
                                <td>
                                    <?php echo isset($company['users_count']) && !empty($company['users_count']) ? '<a href="/admin/user/users_list?&company_id=' . _e($company['id']) . '">' . _e($company['users_count']) . '</a>' : ''; ?>
                                </td>
                                <td>
                                    <a href="<?php echo '/admin/company/edit_company/' . $company['id']; ?>"><i class="icon-pencil"></i></a>
                                    <a href="<?php echo '/admin/company/delete_company/' . $company['id']; ?>" onclick="return confirm('Are you sure you want delete this company ?')"><i class="icon-trash"></i></a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" style="text-align:center">
                                <h4>No companies found</h4>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php if(isset($pagination) && !empty($pagination)): ?>
            <div class="pagination">
                <ul>
                    <?php echo $pagination; ?>
                </ul>
            </div>
        <?php endif; ?>
    </div>
</div>
