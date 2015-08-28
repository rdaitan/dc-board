<h2>Login</h2>
<form action="<?php eh(url('user/authenticate')); ?>" method="post">
    <label for="username">Username</label>
    <input type="text" name="username" id="username">
    <label for="password">Password</label>
    <input type="password" name="password" id="password">
    <br>
    <input type="hidden" name="page_next" value="auth_end">
    <button type="submit">Log in</button> or <a href="<?php eh(url('/')) ?>">cancel</a>
</form>
