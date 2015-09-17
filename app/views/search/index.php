<div class="row">
    <h3>Search</h3>
    <form class="" action="<?php eh(url('')); ?>" method="get">
        <select class="" name="type">
            <option value="<?php eh(SearchController::TYPE_THREAD); ?>"
                <?php eh($type == SearchController::TYPE_THREAD ? 'selected' : ''); ?>>Thread</option>
            <option value="<?php eh(SearchController::TYPE_COMMENT); ?>"
                <?php eh($type == SearchController::TYPE_COMMENT ? 'selected' : ''); ?>>Comment</option>
            <option value="<?php eh(SearchController::TYPE_USER); ?>"
                <?php eh($type == SearchController::TYPE_USER ? 'selected' : ''); ?>>User</option>
        </select>
        <input type="text" name="query" id="query" value="<? eh($query); ?>">
        <button type="submit">Search</button>
    </form>
</div>

<div class="row">
<?php foreach ($search->result as $result): ?>
<?php switch ($type): ?>
<?php case SearchController::TYPE_THREAD: ?>
    <div class='thread'>
        <div>
            <a href="<?php eh(url(VIEW_THREAD_URL, array('id' => $result->id))) ?>">
                <?php eh($result->title); ?>
            </a>
            <span class='category-pill'>
                <?php eh($result->category); ?>
            </span>
        </div>
        <div>
            <small>by
                <a href="<?php eh(url(VIEW_USER_URL, array('id' => $result->user_id))); ?>"><?php eh($result->creator->username); ?></a>
            </small>
            <small class='u-pull-right'>
                replies: <i><?php eh($result->replies_count); ?></i>
            </small>

        </div>
    </div>
<?php break; ?>
<?php case SearchController::TYPE_COMMENT: ?>
    <div class="thread-comment">
        <div>
            <small>
                <a href="<?php eh(url(VIEW_COMMENT_URL, array('id' => $result->id))); ?>">#<?php eh($result->id); ?></a>
                <strong><a href="<?php eh(url(VIEW_USER_URL, array('id' => $result->user->id))); ?>"><?php eh($result->user->username); ?></a></strong>
                    <span class='pad'>
                        created at:
                        <?php eh($result->created_at); ?>
                    </span>
                <?php if ($result->created_at != $result->edited_at): ?>
                    edited at:
                    <?php eh($result->edited_at); ?>
                <?php endif; ?>
            </small>
        </div>
        <div>
            <?php echo readable_text($result->body) ?>
        </div>
    </div>
<?php break; ?>
<?php case SearchController::TYPE_USER: ?>
        <a href="<?php eh(url(VIEW_USER_URL, array('id' => $result->id))); ?>">
            <strong><?php eh($result->username); ?></strong>
        </a><br>
        <div style='padding-left: 18px;'>
            <?php eh($result->first_name . ' ' . $result->last_name); ?><br>
            <?php eh($result->email); ?>
        </div>
<?php break; ?>
<?php default: ?>
<?php break; ?>
<?php endswitch; ?>
<?php endforeach; ?>

<?php print_pagination($pagination, $pages) ?>
</div>
