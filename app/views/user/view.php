<div class="row">
    <h2 class='top-margin'><?php eh($user->username) ?></h2>
    <?php eh("{$user->first_name} {$user->last_name}") ?>
    <?php if($auth_user && $user->id == $auth_user->id): ?>
        <a href="<?php eh(url(EDIT_USER_URL)); ?>">EDIT PROFILE</a>
    <?php endif; ?>
</div>
<div class="row">
    <div class="four columns">
        <h5 class='top-margin'>Threads</h5>
        <ul>
            <?php foreach ($threads as $thread): ?>
                <li><a href="<?php eh(url(VIEW_THREAD_URL, array('id' => $thread->id))); ?>">
                    <?php eh($thread->title); ?>
                </a></li>
            <?php endforeach; ?>
        </ul>
    </div>
    <div class="four columns">
        <h5 class='top-margin'>Comments</h5>
        <ul>
            <?php foreach ($comments as $comment): ?>
                <?php if (!$comment->isThreadBody()): ?>
                    <li><a href="<?php eh(url(VIEW_COMMENT_URL, array('id' => $comment->id))); ?>">
                        <?php eh($comment->body); ?>
                    </a></li>
                <?php endif; ?>
            <?php endforeach; ?>
        </ul>
    </div>
    <div class="four columns">
        <h5 class="top-margin">Following</h5>
        <ul>
            <?php foreach ($follows as $follow): ?>
                <li><a href="<?php eh(url(VIEW_THREAD_URL, array('id' => $follow->thread_id))); ?>">
                    <?php eh($follow->thread_title); ?>
                </a></li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>
