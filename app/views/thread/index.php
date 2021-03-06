<div class="row">
    <div class="col-md-8">
        <div>
            <h1>All Threads</h1>
        </div>
        <div  id="thread_list">
            <?php foreach ($threads as $thread): ?>
                <div class='plank'>
                    <a href="<?php eh(url(VIEW_THREAD_URL, array('thread_id' => $thread->id))) ?>">
                        <?php eh($thread->title); ?>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
        <div>
            <!--Pagination-->
            <?php print_pagination($pagination, $pages); ?>
        </div>
    </div>
    <div class="col-md-4">
        <!--Login/Logout/Signup-->
        <div>
            <?php if ($auth_user):?>
                <h3>Welcome, <?php eh($auth_user->username); ?>!</h3>
            <?php else: ?>
                <h3>Hello, guest!</h3>
            <?php endif; ?>
        </div>
        <div class='offset-top offset-bottom'>
            <?php if ($auth_user): ?>
                <a href="<?php eh(url(CREATE_THREAD_URl)); ?>" class="btn btn-block btn-primary">Create Thread</a>
                <hr>
                <a href="<?php eh(url(LOGOUT_URL)); ?>" class="btn btn-block btn-danger">Log out</a>
            <?php else: ?>
                <a href="<?php eh(url(REGISTER_URL)); ?>" class="btn btn-block btn-primary">Sign Up</a>
                <a href="<?php eh(url(LOGIN_URL)); ?>" class="btn btn-block btn-success">Log in</a>
            <?php endif; ?>
        </div>
    </div>
</div>
