<h3>Followed threads</h3>
<ul>
    <?php foreach ($threads as $thread): ?>
        <li><a href="<?php eh(url(VIEW_THREAD_URL, array('id' => $thread->id))); ?>"><?php eh($thread->title) ?></a></li>
    <?php endforeach; ?>
</ul>
