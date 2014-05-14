<div class="row-fluid">
    <div class="span12">
        <div style="float: left">
            <a href="<?php echo '/admin/room/edit_room/0'; ?>" class="uibutton confirm" style="display:inline-block"><i class="icon-plus"></i>Add Room</a>
        </div>
        <div style="float: right">
            <?php $this->utility->draw_search_form('rooms_list', '/admin/room/rooms_list?'); ?>
        </div>  
    </div>
</div>
<br>
<div class="row-fluid">
    <div class="span12 widget clearfix">
         <div class="widget-header">
             <span>
                <i class="icon-list"></i>
                Room List
            </span>
        </div>
        <div class="widget-content">
            <table class="table table-bordered table-striped">
                <col span="1"/>
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
                                '/admin/room/rooms_list', array(
                            'room_name' => 'Name',
                            'room_description' => 'Description',
                            'room_location' => 'Location',
                                )
                        );
                        ?>
                        <th>Bookings count</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (isset($rooms) && !empty($rooms)): ?>
                        <?php foreach ($rooms as $room): ?>                        
                            <tr>
                                <td>                                
                                    <?php if (isset($room['thumb_name']) && !empty($room['thumb_name']) && file_exists($room['thumb_rel_path'] . $room['thumb_name'])): ?>
                                        <img src="<?php echo base_url() . $room['thumb_rel_path'] . $room['thumb_name']; ?>" class="img-polaroid user-department-list" alt="Company icon" >
                                    <?php else: ?>
                                        <img src="/img/main/no_picture.png" class="department-icon user-department-list" alt="Company pic" >
                                    <?php endif; ?>                                    
                                </td>
                                <td>
                                    <?php echo isset($room['name']) && !empty($room['name']) ? _e($room['name']) : ''; ?>
                                </td>
                                <td>
                                    <?php echo isset($room['description']) && !empty($room['description']) ? _e($room['description']) : ''; ?>
                                </td>
                                <td>
                                    <?php echo isset($room['location_name']) && !empty($room['location_name']) ? _e($room['location_name']) : ''; ?>
                                </td>
                                <td>
                                    <?php echo isset($room['bookings_count']) && !empty($room['bookings_count']) ? '<a href="/admin/booking/booking_list?&room_id=' . _e($room['id']) . '&location_id=' . _e($room['location_id']) . '">' . _e($room['bookings_count']) . '</a>' : ''; ?>
                                </td>
                                <td>
                                    <a href="<?php echo '/admin/room/edit_room/' . $room['id']; ?>"><i class="icon-pencil"></i></a>
                                    <a href="<?php echo '/admin/room/delete_room/' . $room['id']; ?>" onclick="return confirm('Are you sure you want delete this room ?')"><i class="icon-trash"></i></a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" style="text-align:center">
                                <h4>No rooms found</h4>
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
