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
    <form action="<?php eh(url('', array('id' => $comment->id))); ?>" method="post">
        <label for="body">Edit Comment</label>
        <textarea class='u-full-width' id='body' name="body"><?php eh($comment->body); ?></textarea>
        <input type="hidden" name="page_next" value="edit_end">
        <button type="submit">Save</button> or
        <a href="<?php eh(url(VIEW_THREAD_URL, array('id' => $thread->id))); ?>">cancel</a>
    </form>
</div>
