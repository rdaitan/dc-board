<h4><?php eh($user->username) ?></h4>
<strong><?php eh("{$user->first_name} {$user->last_name}") ?></strong>

<!--TESTING ONLY-->
<a href="<?php eh(url(EDIT_USER_URL)); ?>">EDIT PROFILE</a>

<h5>Threads</h5>
<ul>
    <?php foreach ($threads as $thread): ?>
        <li><a href="<?php eh(url(VIEW_THREAD_URL, array('id' => $thread->id))); ?>">
            <?php eh($thread->title); ?>
        </a></li>
    <?php endforeach; ?>
</ul>

<h5>Comments</h5>
<ul>
    <?php foreach ($comments as $comment): ?>
        <?php if (!$comment->isThreadBody()): ?>
            <li><a href="<?php eh(url(VIEW_THREADS_URL, array('id' => $comment->id))); ?>">
                <?php eh($comment->body); ?>
            </a></li>
        <?php endif; ?>
    <?php endforeach; ?>
</ul>
