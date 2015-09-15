<h4><?php eh($user->username) ?></h4>
<strong><?php eh("{$user->first_name} {$user->last_name}") ?></strong>

<h5>Threads</h5>
<ul>
    <?php foreach ($threads as $thread): ?>
        <li><a href="<?php eh(url(VIEW_THREAD_URL, array('id' => $thread->id))); ?>">
            <?php eh($thread->title); ?>
        </a></li>
    <?php endforeach; ?>
</ul>

<a href="<?php eh(url(VIEW_USER_THREADS_URL)); ?>">See more</a>

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

<a href="<?php eh(url(VIEW_USER_COMMENTS_URL, array('id' => $user->id))); ?>">See more</a>
