<h1><?php eh($thread->title) ?></h1>

<?php foreach ($comments as $n => $comment) {?>
    <div class="comment">
        <div class="meta">
            <?php eh(($n + 1) . ": {$comment->username} {$comment->created}"); ?><br />
            <?php eh($comment->body) ?><br />
        </div>
    </div>
<?php } ?>
