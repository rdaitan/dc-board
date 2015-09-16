<div class="row">
    <h3>Login</h3>
</div>
<div class="row">
    <?php if (isset($error)):?>
        <div class="red">
            <?php eh($error); ?>
        </div>
    <?php endif; ?>
</div>
<div class="row">
    <form action="<?php eh(url(LOGIN_URL)); ?>" method="post">
            <div class="row">
                <div class="six columns">
                    <label for="username">Username</label>
                    <input class='u-full-width'type="text" name="username" id="username" value='<?php eh(Param::get('username')); ?>'>
                </div>
                <div class="six columns">
                    <label for="password">Password</label>
                    <input class='u-full-width'type="password" name="password" id="password">
                </div>
            </div>
            <div class="row">
                <input type="hidden" name="page_next" value="auth_end">
                <button type="submit">Log in</button> or
                <a href="<?php eh(url(APP_URL)) ?>">cancel</a>
            </div>
    </form>
</div>
