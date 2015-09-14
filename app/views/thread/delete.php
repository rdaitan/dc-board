<h2>Delete thread?</h2>
<p>
    Are you sure you want to delete this thread?
</p>
<a href="<?php eh(url(DELETE_THREAD_URL, array('id' => $thread->id, 'page_next' => 'delete_end'))); ?>">Yes</a>
<a href="<?php eh(url(VIEW_THREAD_URL, array('id' => $thread->id))); ?>">No</a>
