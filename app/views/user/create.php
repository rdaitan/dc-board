<div class="row">
    <div><h3>Register</h3></div>
    <!--Validation-->
    <?php if ($user->hasError()): ?>
        <div class='red'>
            <?php if (!empty($user->validation_errors['username']['length'])): ?>
                <div>
                    <em>Username</em> must be between
                    <?php eh($user->validation['username']['length'][1]); ?> and
                    <?php eh($user->validation['username']['length'][2]); ?> characters
                    in length.
                </div>
            <?php endif; ?>
            <?php if (!empty($user->validation_errors['username']['chars'])): ?>
                <div>
                    <em>Username</em> must only contain alphanumeric characters or underscore.
                </div>
            <?php endif; ?>
            <?php if (!empty($user->validation_errors['username']['unique'])): ?>
                <div>
                    <em>Username</em> must be unique.
                </div>
            <?php endif; ?>
            <?php if (!empty($user->validation_errors['first_name']['length'])): ?>
                <div>
                    <em>First name</em> must be between
                    <?php eh($user->validation['first_name']['length'][1]); ?> and
                    <?php eh($user->validation['first_name']['length'][2]); ?> characters
                    in length.
                </div>
            <?php endif; ?>
            <?php if (!empty($user->validation_errors['first_name']['chars'])): ?>
                <div>
                    <em>First name</em> must only contain alphabetic characters and spaces.
                </div>
            <?php endif; ?>
            <?php if (!empty($user->validation_errors['last_name']['length'])): ?>
                <div>
                    <em>Last name</em> must be between
                    <?php eh($user->validation['last_name']['length'][1]); ?> and
                    <?php eh($user->validation['last_name']['length'][2]); ?> characters
                    in length.
                </div>
            <?php endif; ?>
            <?php if (!empty($user->validation_errors['last_name']['chars'])): ?>
                <div>
                    <em>Last name</em> must only contain alphabetic characters and spaces.
                </div>
            <?php endif; ?>
            <?php if (!empty($user->validation_errors['email']['length'])): ?>
                <div>
                    <em>Email</em> must be between
                    <?php eh($user->validation['email']['length'][1]); ?> and
                    <?php eh($user->validation['email']['length'][2]); ?> characters
                    in length.
                </div>
            <?php endif; ?>
            <?php if (!empty($user->validation_errors['email']['unique'])): ?>
                <div>
                    <em>Email</em> must be unique.
                </div>
            <?php endif; ?>
            <?php if (!empty($user->validation_errors['email']['chars'])): ?>
                <div>
                    <em>Email</em> must follow email format.
                </div>
            <?php endif; ?>
            <?php if (!empty($user->validation_errors['password']['length'])): ?>
                <div>
                    <em>Password</em> must be between
                    <?php eh($user->validation['password']['length'][1]); ?> and
                    <?php eh($user->validation['password']['length'][2]); ?> characters
                    in length.
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>
    <div>
        <!--Registration form-->
        <form action="<?php eh(url('')); ?>" method="post">
            <label for='username'>Username</label>
            <input class='u-full-width' type="text" name="username" id="username" value="<?php eh(Param::get('username')); ?>">
            <div class="row">
                <div class="six columns">
                    <label for="first_name">First name</label>
                    <input class='u-full-width' type="text" name="first_name" id="first_name" value="<?php eh(Param::get('first_name')); ?>">
                </div>
                <div class="six columns">
                    <label for="last_name">Last name</label>
                    <input class='u-full-width' type="text" name="last_name" id="last_name" value="<?php eh(Param::get('last_name')); ?>">
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-10">
                </div>
            </div>
            <div class="form-group">
                <label class='col-sm-2 control-label' for="email">Email</label>
                <div class="col-sm-10">
                    <input class='form-control' type="email" name="email" id="email" value="<?php eh(Param::get('email')); ?>">
                </div>
            </div>
            <div class="form-group">
                <label class='col-sm-2 control-label' for="password">Password</label>
                <div class="col-sm-10">
                    <input class='form-control' type="password" name="password" id="password" value="<?php eh(Param::get('password')); ?>">
                </div>
            </div>
            <div class="form-group">
                <input type="hidden" name="page_next" value="create_end">
                <div class="col-sm-10 col-sm-offset-2">
                    <button class='btn btn-primary' type="submit">Register</button> or
                    <a href="<?php eh(url(APP_URL)); ?>">cancel</a>
                </div>
            </div>
        </form>
    </div>
</div>
