<?php if ($comment->hasError()): ?>
    <div class="alert alert-danger">
        <h4 class="alert-heading">Validation error!</h4>

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
<!-- edit comment form -->
<form class='form-horizontal' action="<?php eh(url('')); ?>" class="well" method="post">
    <div class="form-group">
        <div class="col-sm-12">
            <h3>Create comment</h3>
        </div>
    </div>
    <div class="form-group">
        <label class='col-sm-2 control-label' for="body">Comment</label>
        <div class="col-sm-10">
            <textarea class='form-control' id='body' name="body"><?php eh($comment->body); ?></textarea>
        </div>
    </div>
    <div class="form-group">
        <input type="hidden" name="thread_id" value="<?php eh($thread->id) ?>">
        <input type="hidden" name="page_next" value="edit_end">
        <div class="col-sm-10 col-sm-offset-2">
            <button type="submit" class="btn btn-primary">Submit</button> or
            <a href="<?php eh(url(VIEW_THREAD_URL, array('thread_id' => $thread->id))); ?>">cancel</a>
        </div>
    </div>
</form>
