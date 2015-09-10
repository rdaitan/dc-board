<div class="row">
    <div class="col-md-6 col-md-offset-3">
        <?php if (isset($error)):?>
            <div class="alert alert-danger">
                <h4>Error</h4>
                <div><?php eh($error); ?></div>
            </div>
        <?php endif; ?>
        <form class='form-horizontal' action="<?php eh(url(LOGIN_URL)); ?>" method="post">
            <div class="form-group">
                <div class="col-sm-12">
                    <h3>Login</h3>
                </div>
            </div>
            <div class="form-group">
                <label class='control-label col-sm-2' for="username">Username</label>
                <div class="col-sm-10">
                    <input class='form-control'type="text" name="username" id="username" value='<?php eh(Param::get('username')); ?>'>
                </div>
            </div>
            <div class="form-group">
                <label class='control-label col-sm-2' for="password">Password</label>
                <div class="col-sm-10">
                    <input class='form-control'type="password" name="password" id="password" value='<?php eh(Param::get('password')); ?>'>
                </div>
            </div>
            <div class="form-groupl">
                <input type="hidden" name="page_next" value="auth_end">
                <div class="col-sm-10 col-sm-offset-2">
                    <button class='btn btn-primary' type="submit">Log in</button> or
                    <a href="<?php eh(url(APP_URL)) ?>">cancel</a>
                </div>
            </div>
        </form>
    </div>
</div>
