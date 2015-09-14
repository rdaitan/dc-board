<h2><?php eh($thread->title) ?></h2>
<div class="plank">
    <small>
        <a href="<?php eh($comment->url); ?>">no.<?php eh($comment->id); ?></a>
        <?php eh($comment->username); ?>
        <?php eh($comment->created_at); ?>
        <?php
            if ($comment->created_at != $comment->edited_at):
                eh($comment->edited_at);
            endif;
        ?>
    </small>
    <br />
    <?php echo readable_text($comment->body) ?>
    <?php if ($comment->isOwnedBy($auth_user)): ?>
        <a href="<?php eh(url(EDIT_COMMENT_URL, array('id' => $comment->id))); ?>">edit</a>
    <?php endif; ?>
</div>
