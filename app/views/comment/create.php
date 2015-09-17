<div class="row">
    <h3><?php eh($thread->title); ?></h3>
</div>
<div class="row">
    <a href='<?php eh(url(VIEW_THREAD_URL, array('id' => $thread->id))); ?>'>&larr; Back to thread</a>
</div>
<div class="row">
    <?php if ($comment->hasError()): ?>
        <div class="red">
            <?php if (!empty($comment->validation_errors['username']['char'])): ?>
                <div>
                    <em>Your name</em> must only have letters and spaces.
                </div>
            <?php endif; ?>
            <?php if (!empty($comment->validation_errors['body']['length'])): ?>
                <div>
                    <em>Comment</em> must be between
                    <?php eh($comment->validation['body']['length'][1]); ?> and
                    <?php eh($comment->validation['body']['length'][2]); ?> characters
                    in length.
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>
<div class="row">
    <!--Comment form-->
    <form action="<?php eh(url(POST_COMMENT_URL)); ?>" method="post">
        <label for="body">Comment</label>
        <textarea class='u-full-width' id='body' name="body"><?php eh(Param::get('body')); ?></textarea>
        <input type="hidden" name="thread_id" value="<?php eh($thread->id) ?>">
        <input type="hidden" name="page_next" value="create_end">
        <button type="submit">Submit</button> or
        <a href="<?php eh(url(VIEW_THREAD_URL, array('id' => $thread->id))); ?>">cancel</a>
    </form>
</div>
