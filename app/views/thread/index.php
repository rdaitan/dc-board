<h1>All Threads</h1>
<?php if($authUser) {?>
    <h2>Welcome, <?php eh($authUser->username); ?>!</h2>
<?php } ?>
<ul>
    <?php foreach($threads as $thread) { ?>
        <li><a href="<?php eh(url('thread/view', array('thread_id' => $thread->id))) ?>">
            <?php eh($thread->title); ?>
        </a></li>
    <?php } ?>
    <a href="<?php eh(url('thread/create')); ?>" class="btn btn-large btn-primary">Create</a>
</ul>
