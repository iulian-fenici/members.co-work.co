
<div class="row-fluid">
    <div class="span12">
        <div style="float:left;">
            <a href="<?php echo '/admin/location/edit_location/0'; ?>" class="uibutton large confirm" style="display:inline-block"><i class="icon-plus"></i>Add Location</a>
        </div>
        <div style="float: right">
            <?php $this->utility->draw_search_form('locations_list', '/admin/location/locations_list?'); ?>
        </div>  
    </div>
</div>

<br>
<div class="row-fluid">
    <div class="span12 widget clearfix">
        <div class="widget-header">
             <span>
                <i class="icon-list"></i>
                Location List
            </span>
        </div>
        <div class="widget-content">
            <table class="table table-bordered table-striped dataTable">
                <col span="1"/>
                <col span="1"/>
                <col span="1"/>
                <col span="1"/>
                <col span="1"/>
                <thead>
                    <tr>
                        <?php
                        echo $this->utility->draw_table_header(
                                '/admin/location/locations_list', array(
                            'location_name' => 'Name',
                            'location_description' => 'Description'
                                )
                        );
                        ?>
                        <th>Companies Count</th>
                        <th>Rooms Count</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (isset($locations) && !empty($locations)): ?>
                        <?php foreach ($locations as $location): ?>                        
                            <tr>
                                <td>
                                    <?php echo isset($location['name']) && !empty($location['name']) ? _e($location['name']) : ''; ?>
                                </td>
                                <td>
                                    <?php echo isset($location['description']) && !empty($location['description']) ? _e($location['description']) : ''; ?>
                                </td>
                                <td>
                                    <?php echo isset($location['companies_count']) && !empty($location['companies_count']) ? '<a href="/admin/company/companies_list?&company_location_id=' . _e($location['id']) . '">' . $location['companies_count'] . '</a>' : ''; ?>
                                </td>
                                <td>
                                    <?php echo isset($location['rooms_count']) && !empty($location['rooms_count']) ? '<a href="/admin/room/rooms_list?&room_location=' . _e($location['name']) . '">' . $location['rooms_count'] . '</a>' : ''; ?>
                                </td>
                                <td>
                                    <a href="<?php echo '/admin/location/edit_location/' . $location['id']; ?>"><i class="icon-pencil"></i></a>
                                    <a href="<?php echo '/admin/location/delete_location/' . $location['id']; ?>" onclick="return confirm('Are you sure you want delete this location ?')"><i class="icon-trash"></i></a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" style="text-align:center">
                                <h4>No locations found</h4>
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
