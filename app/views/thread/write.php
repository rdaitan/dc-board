<h2><?php eh($thread->title); ?></h2>

<?php if($comment->hasError()) { ?>
    <div class="alert alert-block">
        <h4 class="alert-heading">Validation error!</h4>

        <?php if(!empty($comment->validation_errors['username']['length'])) { ?>
            <div>
                <em>Comment</em> must be between
                <?php eh($comment->validation['body']['length'][1]); ?> and
                <?php eh($comment->validation['body']['length'][2]); ?> characters
                in length.
            </div>
        <?php } ?>
    </div>
<?php } ?>

<!--Comment form-->
<form action="<?php eh(url('thread/write')); ?>" class="well" method="post">
    <label for="">Your Name</label>
    <input type="text" class="span2" name="username" value="<?php eh(Param::get('username')); ?>">
    <label for="">Comment</label>
    <textarea name="body"><?php eh(Param::get('body')); ?></textarea>
    <br>
    <input type="hidden" name="thread_id" value="<?php eh($thread->id) ?>">
    <input type="hidden" name="page_next" value="write_end">
    <button type="submit" class="btn btn-primary">Submit</button>
</form>
