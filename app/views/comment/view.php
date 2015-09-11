<h2><?php eh($comment->thread_title) ?></h2>
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
    <?php if ($comment->edit_url): ?>
        <a href="<?php eh($comment->edit_url); ?>">edit</a>
    <?php endif; ?>
</div>
