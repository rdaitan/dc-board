<h2>Register</h2>

<form action="<?php eh(url('')); ?>" method="post">
    <label for="username">Username</label>
    <input type="text" name="username" id="username">
    <label for="email">Email</label>
    <input type="email" name="" id="email">
    <label for="password">Password</label>
    <input type="password" name="password" id="password">
    <br>
    <input type="hidden" name="page_next" value="create_end">
    <button type="submit">Register</button> or
    <a href="">cancel</a>
</form>
