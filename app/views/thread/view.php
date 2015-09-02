<div class="row">
    <div class="col-md-12">
        <div>
            <h1><?php eh($thread->title) ?></h1>
            <a href='<?php eh(url(APP_URL)); ?>'>&larr; All threads</a>
        </div>
        <div class="offset-top">
            <?php foreach ($comments as $n => $comment) {?>
                <div class="plank">
                    <?php
                    $comment_num = ($page - 1) * ThreadController::COMMENTS_PERPAGE + $n  + 1;
                    eh(("{$comment_num} : {$comment->username} {$comment->created}")); ?><br />
                    <?php echo readable_text($comment->body) ?>
                </div>
            <?php } ?>

            <!--pagination-->
            <?php printPageLinks($pagination, $pages); ?>

            <!--Comment Form-->
            <hr>
            <?php if(User::getAuthenticated()) {?>
                <form class='form-horizontal' action="<?php eh(url('comment/create')); ?>" class="well" method="post">
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
            <?php } else {?>
                <a href="<?php eh(url('user/authenticate')) ?>" class="btn btn-primary">Log in to comment</a>
            <?php }?>
        </div>
    </div>
</div>
