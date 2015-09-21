<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>dc-board | <?php eh($title) ?></title>
    <link href="https://rawgit.com/dhg/Skeleton/master/css/normalize.css" rel="stylesheet" type="text/css">
    <link href="https://rawgit.com/dhg/Skeleton/master/css/skeleton.css" rel="stylesheet" type="text/css">
    <!-- <link href='https://fonts.googleapis.com/css?family=Source+Code+Pro' rel='stylesheet' type='text/css'> -->
    <link href='https://fonts.googleapis.com/css?family=Source+Sans+Pro' rel='stylesheet' type='text/css'>
    <!-- <link href="/css/dietcake-board.css" rel="stylesheet" type="text/css"> -->
    <link rel="stylesheet" href="https://cdn.rawgit.com/rdaitan/b3fb99b97ef11fe6b1ef/raw/d2055bdbe78a73b438f9521e61c0e43b42d04bd9/dietcake-board.css">
</head>

<body>
    <div class="nav">
        <!--Logo-->
        <span class="brand"><a href="<?php eh(url(APP_URL)); ?>">dc-board</a></span>
        <!--Search bar-->
        <form class="" action="<?php eh(url(SEARCH_URL)); ?>" method="get">
            <select class="" name="type">
                <option value="<?php eh(SearchController::TYPE_THREAD); ?>">Thread</option>
                <option value="<?php eh(SearchController::TYPE_COMMENT); ?>">Comment</option>
                <option value="<?php eh(SearchController::TYPE_USER); ?>">User</option>
            </select>
            <input type="text" name="query" id="query" placeholder="search">
            <button type="submit">Search</button>
        </form>
        <ul class="user-panel u-pull-right">
            <?php if ($auth_u = User::getAuthenticated()): ?>
                <li><a id='follow' href="<?php eh(url(VIEW_FOLLOWS_URL)); ?>">follows</a></li>
                <li><a href="<?php eh(url(CREATE_THREAD_URL)) ?>">create_thread</a></li>
                <li><a href="<?php eh(url(VIEW_USER_URL)); ?>"><?php eh($auth_u->username); ?></a></li>
                <li><a href="<?php eh(url(LOGOUT_URL)); ?>">logout</a></li>
            <?php else: ?>
                <li><a href="<?php eh(url(LOGIN_URL)); ?>">login</a></li>
                <li><a href="<?php eh(url(REGISTER_URL)); ?>">register</a></li>
            <?php endif; ?>
        </ul>
    </div>
    <div class="container">

        <?php echo $_content_ ?>
    </div>
    <script src="https://code.jquery.com/jquery-1.11.3.min.js"></script>
    <!-- <script src='/js/follow_updater.js'></script> -->
    <script src='https://cdn.rawgit.com/rdaitan/85add4bed682318ec817/raw/a27ad94619ba83c12ad6a3cf21f19be6e8180212/follow_updater.js'></script>
    <script>
    console.log(<?php eh(round(microtime(true) - TIME_START, 3)) ?> + 'sec');
    </script>
</body>
</html>
