<h2>Trending</h2>
<ul>
    <?php foreach ($threads as $thread): ?>
        <li>
            <a href="<?php eh(url(VIEW_THREAD_URL, array('id' => $thread->id))); ?>"><?php eh($thread->title); ?></a>
            <strong><?php eh($thread->count); ?></strong>
        </li>
    <?php endforeach; ?>
</ul>
