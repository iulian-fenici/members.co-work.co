<script type="text/javascript">
    $(document).ready(function() {
        
        $('.comment_buttons').on('click','.edit_comment',function(){
            comment = $(this).parents('.comment_cont').find('.comment_text');
            comment.wrap('<textarea>'+comment.text()+'</textarea>');
            comment.remove();
            $(this).parent().prepend('<a class="update" data-id="'+$(this).data('id')+'">update</a>')
            $(this).remove();
        });
        
         $('.comment_buttons').on('click','.update',function(){             
             comment = $(this).parents('.comment_cont').find('textarea');
             
             $.ajax({
                 url: "<?php echo (isset($admin)&&$admin)?'/admin':'/merchant'?>/ajax/update_comment",
                 type: "POST",
                 data:{
                     id:$(this).data('id'),
                     text:comment.val()
                    },
                 success: function(ret)
                    {
                        console.log(ret);
                    }
             });
             comment.wrap('<div class="comment_text">'+htmlspecialchars(comment.val(), 'ENT_QUOTES')+'</div>');
             comment.remove();
             $(this).text('edit');
             $(this).attr('class','edit_comment');
         });
         
         $('.delete_comment').on('click',function()
         {
             if (confirm('delete?'))
                 {                
                     window.location = "/comments/delete_comment/"+$(this).data('commet_id')+"/"+$(this).data('announcements_id');
                 }
                 
         });
        
    });

</script> 
<?php if (isset($announcements) && !empty($announcements['id'])): ?>
<div class="row-fluid">
    <div class="span12 widget clearfix"> 
        <div class="widget-header">
            <span class="announce_header_cont">
                <i class="icon-table"></i>
                <div class='announce_title'><?php echo $announcements['title'] ?></div>
                <?php if (isset($announcements['user'])&&!empty($announcements['user'])):?>
                    <div class='announce_crated_by'>Created by: <?php echo $announcements['user'] ?></div>
                <?php endif?>
            </span>
        </div>
        <div class="widget-content">            
                <div><?php echo nl2br(_e($announcements['description'])) ?></div>
                <?php if ($announcements['date_from'] != '0000-00-00 00:00:00'): ?>
                    <span>From: <?php echo date('d/m/Y',strtotime($announcements['date_from'])); ?></span>&nbsp&nbsp&nbsp&nbsp
                <?php endif ?>
                <?php if ($announcements['date_till'] != '0000-00-00 00:00:00'): ?>
                    <span>Till: <?php echo date('d/m/Y',strtotime($announcements['date_till'])); ?></span>
                <?php endif ?>                    
        </div>
    </div>
</div>
<?php if(isset($comments)&&!empty($comments)):?>
Recent comments:
<?php foreach ($comments as $comment):?>
<div class="comment_cont">
    <div class="comment_from">From: <?php echo $comment->username; ?></div>
    <div class="comment_date">Date: <?php echo $comment->date; ?></div>
    <div class="comment_text"><?php echo nl2br(_e($comment->comment_text)); ?></div>    
    <?php if($this->session->userdata('user_id')==$comment->user_id || (isset($admin) && $admin)):?>
    <div class="comment_buttons">
        <a class="edit_comment" data-id="<?php echo $comment->id?>">edit</a> 
        <a class="delete_comment" data-commet_id="<?php echo $comment->id?>" data-announcements_id="<?php echo $announcements['id']?>">delete</a>
    </div>
    <div class="clearfix"></div>
    <?php endif?>
</div>
<?php endforeach?>
<?php endif?>
Comment: <?php if ($this->session->flashdata('error')) echo $this->session->flashdata('error') ?>
<form method='POST' action="/comments/add_comment/<?php echo $announcements['id']?>">
    <textarea name="comment_text"></textarea>
    <br>
    <input type="submit" name="submit_comment" value="Send">
</form>
<?php else: echo 'There is no announcement with this id'?>
<?php endif ?>