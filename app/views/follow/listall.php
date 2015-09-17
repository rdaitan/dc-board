<div class="row">
    <div class="six columns">
        <h3>Updates</h3>
        <ul>
            <?php foreach ($updated_threads as $thread): ?>
                <li><a href="<?php eh(url(REDIRECT_FOLLOW_URL, array('id' => $thread->follow_id))); ?>"><?php eh($thread->title) ?></a><strong><?php eh($thread->update_count); ?></strong></li>
            <?php endforeach; ?>
        </ul>
    </div>
    <div class="six columns">
        <h3>Follows</h3>
        <ul>
            <?php foreach ($threads as $thread): ?>
                <li><a href="<?php eh(url(VIEW_THREAD_URL, array('id' => $thread->id))); ?>"><?php eh($thread->title) ?></a></li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>
