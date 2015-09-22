<div class="row">
    <h3><?php eh($thread->title) ?></h3>
</div>
<div class="row">
    <a href='<?php eh(url(VIEW_THREAD_URL, array('id' => $comment->thread_id))); ?>'>&larr; Back to thread</a>
</div>
<div class="row">
    <div class="thread-comment">
        <div>
            <small>
                <a href="<?php eh(url(VIEW_COMMENT_URL, array('id' => $comment->id))); ?>">#<?php eh($comment->id); ?></a>
                <strong><a href="<?php eh(url(VIEW_USER_URL, array('id' => $comment->user->id))); ?>"><?php eh($comment->user->username); ?></a></strong>
                    <span class='pad'>
                        created at:
                        <?php eh($comment->created_at); ?>
                    </span>
                <?php if ($comment->created_at != $comment->modified_at): ?>
                    modified at:
                    <?php eh($comment->modified_at); ?>
                <?php endif; ?>
            </small>
        </div>
        <div>
            <?php echo readable_text($comment->body) ?>
        </div>
    </div>
</div>


<!--Embed images-->
<script type="text/javascript" src="https://cdn.rawgit.com/rdaitan/14d816b7c1037671b8b6/raw/65fca1bc5041b7d9b10d7bcbad159af8f1b3a513/img_embed.js"></script>
