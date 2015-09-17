<div class="row">
    <h3>Delete comment?</h3>
</div>
<div class="row">
    <p>
        Are you sure you want to delete this comment?
    </p>
    <a href="<?php eh(url(DELETE_COMMENT_URL, array('id' => $comment->id, 'page_next' => 'delete_end'))); ?>">Yes</a>
    <a href="<?php eh(url(VIEW_COMMENT_URL, array('id' => $comment->id))); ?>">No</a>
</div>
