<script type="text/javascript">
    $(document).ready(function() {
        
    });
</script>
<div class="row-fluid">
    <div class="row-fluid" style="margin-bottom: 20px;">
        <div class="span12">    
            <div style="float: left">
                <a href="/merchant/announcement/add_announcement" class="uibutton large confirm">Add announcement</a>
            </div>
            <div style="float: right">
                <?php $this->utility->draw_search_form('announcements_list', '/merchant/announcement/announcements_list?'); ?>
            </div>  
        </div>
    </div>
    <div class="row-fluid" >
        <div class="span12 widget clearfix">
            <div class="widget-header">
                <span>
                    <i class="icon-list"></i>
                    Announcements
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
                            <?php
                            echo $this->utility->draw_table_header(
                                    '/merchant/announcement/announcements_list', array(
                                'announcement_title' => 'Subject',
                                'announcement_description' => 'Description'
                                    )
                            );
                            ?>
                            <th>Dates</th>
                            <th>Comments</th>
                            <th>Active</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (isset($announcements) && !empty($announcements)): ?>
                            <?php foreach ($announcements as $announcement): ?>                        
                                <tr class="announcement_row">
                                    <td>                                
                                        <?php echo isset($announcement['title']) && !empty($announcement['title']) ? '<a href="/merchant/announcement/view/'.$announcement['id'].'">'._e($announcement['title']).'</a>' : ''; ?>
                                    </td>
                                    <td>
                                        <?php echo isset($announcement['description']) && !empty($announcement['description']) ? '<a href="/merchant/announcement/view/'.$announcement['id'].'">'._e($announcement['description']).'</a>' : ''; ?>
                                    </td>
                                    <td>
                                        <?php echo isset($announcement['date_from']) && !empty($announcement['date_from']) && $announcement['date_from'] != '0000-00-00 00:00:00' ? date('d/m/Y', strtotime($announcement['date_from'])) : '...'; ?> - 
                                        <?php echo isset($announcement['date_till']) && !empty($announcement['date_till']) && $announcement['date_till'] != '0000-00-00 00:00:00' ? date('d/m/Y', strtotime($announcement['date_till'])) : '...'; ?>
                                    </td>
                                    <td>
                                        <a href="/merchant/announcement/view/<?php echo $announcement['id'] ?>"><?php echo $announcement['comments_count'] ?></a>
                                    </td>
                                    <td>
                                        <?php echo isset($announcement['active']) && !empty($announcement['active']) ? 'active' : 'not active'; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" style="text-align:center">
                                    <h4>No announcements found</h4>
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
</div>

