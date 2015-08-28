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

<!--Pagination-->
<?php if($pagination->current > 1): ?>
    <a href='?page=<?php echo $pagination->prev ?>'>Previous</a>
<?php else: ?>
    Previous
<?php endif ?>

<?php for($i = 1; $i <= $pages; $i++): ?>
    <?php if($i == $page): ?>
        <?php echo $i ?>
        <?php else: ?>
        <a href='?page=<?php echo $i ?>'><?php echo $i ?></a>
    <?php endif; ?>
<?php endfor; ?>

<?php if(!$pagination->is_last_page): ?>
    <a href='?page=<?php echo $pagination->next ?>'>Next</a>
<?php else: ?>
    Next
<?php endif ?>

<!--Login/Logout/Signup-->
<?php if(User::getAuthUser()) { ?>
    <a href="<?php eh(url('user/logout')); ?>" class="btn btn-danger">Log out</a>
<?php } else { ?>
    <a href="<?php eh(url('user/create')); ?>" class="btn btn-primary">Sign Up</a>
    <a href="<?php eh(url('user/authenticate')); ?>" class="btn btn-success">Log in</a>
<?php } ?>
