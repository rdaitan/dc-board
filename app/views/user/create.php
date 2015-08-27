<h2>Register</h2>

<?php if($user->hasError()) { ?>
    <div class="alert alert-block">
        <h4 class="alert-heading">Validation error!</h4>

        <?php if(!empty($user->validation_errors['username']['length'])) { ?>
            <div>
                <em>Username</em> must be between
                <?php eh($user->validation['username']['length'][1]); ?> and
                <?php eh($user->validation['username']['length'][2]); ?> characters
                in length.
            </div>
        <?php } ?>
        <?php if(!empty($user->validation_errors['email']['length'])) { ?>
            <div>
                <em>Email</em> must be between
                <?php eh($user->validation['email']['length'][1]); ?> and
                <?php eh($user->validation['email']['length'][2]); ?> characters
                in length.
            </div>
        <?php } ?>
        <?php if(!empty($user->validation_errors['password']['length'])) { ?>
            <div>
                <em>Password</em> must be between
                <?php eh($user->validation['password']['length'][1]); ?> and
                <?php eh($user->validation['password']['length'][2]); ?> characters
                in length.
            </div>
        <?php } ?>
    </div>
<?php } ?>

<!--Registration form-->
<form action="<?php eh(url('')); ?>" method="post">
    <label for="username">Username</label>
    <input type="text" name="username" id="username" value="<?php eh(Param::get('username')); ?>">
    <label for="email">Email</label>
    <input type="email" name="email" id="email" value="<?php eh(Param::get('email')); ?>">
    <label for="password">Password</label>
    <input type="password" name="password" id="password" value="<?php eh(Param::get('password')); ?>">
    <br>
    <input type="hidden" name="page_next" value="create_end">
    <button type="submit">Register</button> or
    <a href="">cancel</a>
</form>
