<div class="row">
    <div class="col-md-8">
        <div>
            <h1>All Threads</h1>
        </div>
        <div  id="thread_list">
            <?php foreach($threads as $thread) { ?>
                <div class='plank'>
                    <div>
                        <a href="<?php eh(url('thread/view', array('thread_id' => $thread->id))) ?>">
                            <?php eh($thread->title); ?>
                        </a>
                    </div>
                </div>
            <?php } ?>
        </div>
        <div>
            <!--Pagination-->
            <?php printPageLinks($pagination, $pages); ?>
        </div>
    </div>
    <div class="col-md-4">
        <!--Login/Logout/Signup-->
        <div>
            <?php if($authUser) {?>
                <h3>Welcome, <?php eh($authUser->username); ?>!</h3>
            <?php } else { ?>
                <h3>Hello, guest!</h3>
            <?php } ?>
        </div>
        <div class='offset-top offset-bottom'>
            <?php if(User::getAuthUser()) { ?>
                <a href="<?php eh(url('thread/create')); ?>" class="btn btn-block btn-primary">Create Thread</a>
                <hr>
                <a href="<?php eh(url('user/logout')); ?>" class="btn btn-block btn-danger">Log out</a>
            <?php } else { ?>
                <a href="<?php eh(url('user/create')); ?>" class="btn btn-block btn-primary">Sign Up</a>
                <a href="<?php eh(url('user/authenticate')); ?>" class="btn btn-block btn-success">Log in</a>
            <?php } ?>
        </div>
    </div>
</div>
