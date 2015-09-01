<h1><?php eh($thread->title) ?></h1>

<?php foreach ($comments as $n => $comment) {?>
    <div class="comment">
        <div class="meta">
            <?php eh(($n + 1) . ": {$comment->username} {$comment->created}"); ?><br />
            <?php echo readable_text($comment->body) ?><br />
        </div>
    </div>
<?php } ?>

<!--pagination-->
<?php printPageLinks($pagination, $pages); ?>

<!--Comment Form-->
<?php if(User::getAuthUser()) {?>
    <hr>
    <form action="<?php eh(url('comment/create')); ?>" class="well" method="post">
        <label for="">Comment</label>
        <textarea name="body"><?php eh(Param::get('body')); ?></textarea>
        <br>
        <input type="hidden" name="thread_id" value="<?php eh($thread->id) ?>">
        <input type="hidden" name="page_next" value="create_end">
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
<?php } else {?>
    <a href="<?php eh(url('user/authenticate')) ?>" class="btn btn-primary">Comment</a>
<?php }?>
