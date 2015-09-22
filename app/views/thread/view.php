<div class="row">
    <h3><?php eh($thread->title) ?></h3>
</div>
<!--thread controls-->
<div class="row">
    <a href='<?php eh(url(APP_URL)); ?>'>&larr; All threads</a>
    <?php if ($auth_user): ?>
        <?php if ($thread->isFollowedBy($auth_user)): ?>
            <a href="<?php eh(url(UNFOLLOW_URL, array('id' => $thread->id))); ?>">Unfollow</a>
        <?php else: ?>
            <a href="<?php eh(url(FOLLOW_URL, array('id' => $thread->id))); ?>">Follow</a>
        <?php endif; ?>
    <?php endif; ?>
    <span class='u-pull-right'>
        <?php if ($thread->isAuthor($auth_user)): ?>
            <a href='<?php eh(url(EDIT_THREAD_URL, array('id' => $thread->id))); ?>'>Edit thread</a>
            <a href='<?php eh(url(DELETE_THREAD_URL, array('id' => $thread->id))); ?>'>Delete thread</a>
        <?php endif; ?>
    </span>
</div>
<div class="row">
    <!--comment-->
    <?php foreach ($comments as $n => $comment): ?>
        <div class="<?php eh($comment->isThreadBody() ? 'thread-body' : 'thread-comment'); ?>">
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
                    <span class="u-pull-right">
                        <?php if ($comment->isAuthor($auth_user) && !$comment->isThreadBody()): ?>
                            <a href="<?php eh(url(EDIT_COMMENT_URL, array('id' => $comment->id))); ?>">edit</a>
                            <a href="<?php eh(url(DELETE_COMMENT_URL, array('id' => $comment->id))); ?>">delete</a>
                        <?php endif; ?>
                    </span>
                </small>
            </div>
            <div>
                <?php echo readable_text($comment->body) ?>
            </div>
        </div>
    <?php endforeach; ?>
</div>
<div class="row">
    <?php print_pagination($pagination, $pages); ?>
</div>
<div class="row">
    <?php if (User::getAuthenticated()): ?>
        <form action="<?php eh(url(POST_COMMENT_URL)); ?>" class="well" method="post">
            <label for="body">Comment</label>
            <textarea class='u-full-width' id='body' name="body" placeholder='Wrap URL in [img]...[/img] to embed an image.'><?php eh(Param::get('body')); ?></textarea>
            <input type="hidden" name="thread_id" value="<?php eh($thread->id) ?>">
            <input type="hidden" name="page_next" value="create_end">
            <button type="submit">Submit</button>
        </form>
    <?php else: ?>
        <a href="<?php eh(url(LOGIN_URL)) ?>">Log in to comment</a>
    <?php endif; ?>
</div>


<!--thread view only-->
<script type="text/javascript" src="https://cdn.rawgit.com/rdaitan/14d816b7c1037671b8b6/raw/65fca1bc5041b7d9b10d7bcbad159af8f1b3a513/img_embed.js"></script>
