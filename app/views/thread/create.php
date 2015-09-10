<div class="row">
    <div class="col-md-6 col-md-offset-3">
        <?php if ($thread->hasError() || $comment->hasError()) : ?>
            <div class="alert alert-danger">
                <h4 class="alert-heading">Validation error!</h4>
                <?php if (!empty($thread->validation_errors['title']['length'])) : ?>
                    <div>
                        <em>Title</em> must be between
                        <?php eh($thread->validation['title']['length'][1]); ?> and
                        <?php eh($thread->validation['title']['length'][2]); ?>
                        characters in length.
                    </div>
                <?php endif; ?>
                <?php if (!empty($comment->validation_errors['username']['length'])) : ?>
                    <div>
                        <em>Your name</em> must be between
                        <?php eh($comment->validation['username']['length'][1]); ?> and
                        <?php eh($comment->validation['username']['length'][2]); ?>
                        characters in length.
                    </div>
                <?php endif; ?>
                <?php if (!empty($comment->validation_errors['body']['length'])) : ?>
                    <div>
                        <em>Comment</em> must be between
                        <?php eh($comment->validation['body']['length'][1]); ?> and
                        <?php eh($comment->validation['body']['length'][2]); ?>
                        characters in length.
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <!--Thread creation form-->
        <form action="<?php eh(url('')); ?>" class="form-horizontal" method="post">
            <div class="form-group">
                <div class="col-sm-12">
                    <h3>Create a thread</h3>
                </div>
            </div>
            <div class="form-group">
                <label  class='col-sm-2 control-label' for="">Title</label>
                <div class="col-sm-10">
                    <input type="text" class='form-control' name="title" class="span2" value="<?php eh(Param::get('title')); ?>">
                </div>
            </div>
            <div class="form-group">
                <label class='col-sm-2 control-label' for="">Comment</label>
                <div class="col-sm-10">
                    <textarea class='form-control' name="body"><?php eh(Param::get('body')); ?></textarea>
                </div>
            </div>
            <div class="form-group">
                <input type="hidden" name="page_next" value="create_end">
                <div class="col-sm-offset-2 col-sm-10">
                    <button type="submit" class="btn btn-primary">Submit</button> or
                    <a href="<?php eh(url(APP_URL)) ?>">cancel</a>
                </div>
            </div>
        </form>
    </div>
</div>
