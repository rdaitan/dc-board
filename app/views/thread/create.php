<h1>Create a thread</h1>

<?php if($thread->hasError() || $comment->hasError()) { ?>
    <div class="alert alert-block">
        <h4 class="alert-heading">Validation error!</h4>
        <?php if(!empty($thread->validation_errors['title']['length'])) { ?>
            <div>
                <em>Title</em>must be between
                <?php eh($thread->validation['title']['length'][1]); ?> and
                <?php eh($thread->validation['title']['length'][2]); ?>
                characters in length.
            </div>
        <?php } ?>
        <?php if(!empty($comment->validation_errors['username']['length'])) { ?>
            <div>
                <em>Comment</em>must be between
                <?php eh($comment->validation['username']['length'][1]); ?> and
                <?php eh($comment->validation['username']['length'][2]); ?>
                characters in length.
            </div>
        <?php } ?>
        <?php if(!empty($comment->validation_errors['body']['length'])) { ?>
            <div>
                <em>Body</em>must be between
                <?php eh($comment->validation['body']['length'][1]); ?> and
                <?php eh($comment->validation['body']['length'][2]); ?>
                characters in length.
            </div>
        <?php } ?>
    </div>
<?php } ?>

<!--Thread creation form-->
<form action="<?php eh(url('')); ?>" class="well" method="post">
    <label for="">Title</label>
    <input type="text" name="title" class="span2" value="<?php eh(Param::get('title')); ?>">
    <label for="">Your Name</label>
    <input type="text" class="span2" name="username" value="<?php eh(Param::get('username')); ?>">
    <label for="">Comment</label>
    <textarea name="body"><?php eh(Param::get('body')); ?></textarea>
    <br>
    <input type="hidden" name="page_next" value="create_end">
    <button type="submit" class="btn btn-primary">Submit</button>
</form>
