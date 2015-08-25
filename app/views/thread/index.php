<h1>All Threads</h1>
<ul>
    <?php foreach($threads as $thread) { ?>
        <li><a href="<?php eh(url('thread/view', array('thread_id' => $thread->id))) ?>">
            <?php eh($thread->title); ?>
        </a></li>
    <?php } ?>
</ul>
