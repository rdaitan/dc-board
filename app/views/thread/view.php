<div class="row">
    <div class="col-md-12">
        <div>
            <h1><?php eh($thread->title) ?></h1>
            <a href='<?php eh(url(APP_URL)); ?>'>&larr; All threads</a>
            <?php if ($thread->isOwnedBy($auth_user)): ?>
                <a href='<?php eh(url(EDIT_THREAD_URL, array('id' => $thread->id))); ?>'>Edit thread</a>
                <a href='<?php eh(url(DELETE_THREAD_URL, array('id' => $thread->id))); ?>'>Delete thread</a>
            <?php endif; ?>
        </div>
        <div class="offset-top">
            <?php foreach ($comments as $n => $comment): ?>
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
            <?php endforeach; ?>

            <!--pagination-->
            <?php print_pagination($pagination, $pages); ?>

            <!--Comment Form-->
            <hr>
            <?php if (User::getAuthenticated()): ?>
                <form class='form-horizontal' action="<?php eh(url(POST_COMMENT_URL)); ?>" class="well" method="post">
                    <div class="form-group">
                        <div class="col-sm-12">
                            <h3>Create comment</h3>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class='col-sm-2 control-label' for="body">Comment</label>
                        <div class="col-sm-10">
                            <textarea class='form-control' id='body' name="body"><?php eh(Param::get('body')); ?></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <input type="hidden" name="thread_id" value="<?php eh($thread->id) ?>">
                        <input type="hidden" name="page_next" value="create_end">
                        <div class="col-sm-10 col-sm-offset-2">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </div>
                </form>
            <?php else: ?>
                <a href="<?php eh(url(LOGIN_URL)) ?>" class="btn btn-primary">Log in to comment</a>
            <?php endif; ?>
        </div>
    </div>
</div>
