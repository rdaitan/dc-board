<h2>Login</h2>
<?php if(isset($error)) {?>
    <div class="alert alert-block">
        <h4>Error</h4>
        <div><?php eh($error); ?></div>
    </div>
<?php } ?>
<form action="<?php eh(url('user/authenticate')); ?>" method="post">
    <label for="username">Username</label>
    <input type="text" name="username" id="username" value='<?php eh(Param::get('username')); ?>'>
    <label for="password">Password</label>
    <input type="password" name="password" id="password" value='<?php eh(Param::get('password')); ?>'>
    <br>
    <input type="hidden" name="page_next" value="auth_end">
    <button type="submit">Log in</button> or <a href="<?php eh(url('/')) ?>">cancel</a>
</form>
