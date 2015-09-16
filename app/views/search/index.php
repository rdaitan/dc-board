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

<?php foreach ($search->result as $result): ?>
<?php switch ($type): ?>
<?php case SearchController::TYPE_THREAD: ?>
    <li><a href="<?php eh(url(VIEW_THREAD_URL, array('id' => $result->id))); ?>">
        <?php eh($result->title); ?>
    </a></li>
<?php break; ?>
<?php case SearchController::TYPE_COMMENT: ?>
    <li><a href="<?php eh(url(VIEW_COMMENT_URL, array('id' => $result->id))); ?>">
        <?php eh($result->body); ?>
    </a></li>
<?php break; ?>
<?php case SearchController::TYPE_USER: ?>
    <li><a href="<?php eh(url(VIEW_USER_URL, array('id' => $result->id))); ?>">
        <?php eh($result->username); ?>|
        <?php eh($result->first_name . ' ' . $result->last_name); ?>|
        <?php eh($result->email); ?>
    </a></li>
<?php break; ?>
<?php default: ?>
<?php break; ?>
<?php endswitch; ?>
<?php endforeach; ?>

<?php print_pagination($pagination, $pages) ?>
