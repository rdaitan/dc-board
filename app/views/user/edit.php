
<div class="row">
    <div class="col-md-6 col-md-offset-3">

        <?php if ($auth_user->hasError()): ?>
            <div class="alert alert-danger">
                <h4 class="alert-heading">Validation error!</h4>
                <?php if (!empty($auth_user->validation_errors['first_name']['length'])): ?>
                    <div>
                        <em>First name</em> must be between
                        <?php eh($auth_user->validation['first_name']['length'][1]); ?> and
                        <?php eh($auth_user->validation['first_name']['length'][2]); ?> characters
                        in length.
                    </div>
                <?php endif; ?>
                <?php if (!empty($auth_user->validation_errors['first_name']['chars'])): ?>
                    <div>
                        <em>First name</em> must only contain alphabetic characters and spaces.
                    </div>
                <?php endif; ?>
                <?php if (!empty($auth_user->validation_errors['last_name']['length'])): ?>
                    <div>
                        <em>Last name</em> must be between
                        <?php eh($auth_user->validation['last_name']['length'][1]); ?> and
                        <?php eh($auth_user->validation['last_name']['length'][2]); ?> characters
                        in length.
                    </div>
                <?php endif; ?>
                <?php if (!empty($auth_user->validation_errors['last_name']['chars'])): ?>
                    <div>
                        <em>Last name</em> must only contain alphabetic characters and spaces.
                    </div>
                <?php endif; ?>
                <?php if (!empty($auth_user->validation_errors['password']['match_old'])): ?>
                    <div>
                        Incorrect <em>Password</em>.
                    </div>
                <?php endif; ?>
                <?php if (!empty($auth_user->validation_errors['new_password']['length'])): ?>
                    <div>
                        <em>Password</em> must be between
                        <?php eh($auth_user->validation['new_password']['length'][1]); ?> and
                        <?php eh($auth_user->validation['new_password']['length'][2]); ?> characters
                        in length.
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <!--Edit profile form-->
        <form class='form-horizontal' action="<?php eh(url('')); ?>" method="post">
            <div class="form-group">
                <div class="col-sm-12">
                    <h3>Edit profile</h3>
                </div>
            </div>
            <div class="form-group">
                <label class='col-sm-2 control-label' for="first_name">First name</label>
                <div class="col-sm-10">
                    <input class='form-control' type="text" name="first_name" id="first_name" value="<?php eh($auth_user->first_name); ?>">
                </div>
            </div>
            <div class="form-group">
                <label class='col-sm-2 control-label' for="last_name">Last name</label>
                <div class="col-sm-10">
                    <input class='form-control' type="text" name="last_name" id="last_name" value="<?php eh($auth_user->last_name); ?>">
                </div>
            </div>
            <div class="form-group">
                <label class='col-sm-2 control-label' for="password">Password</label>
                <div class="col-sm-10">
                    <input class='form-control' type="password" name="password" id="password" value="">
                </div>
            </div>
            <div class="form-group">
                <label class='col-sm-2 control-label' for="new_password">New Password</label>
                <div class="col-sm-10">
                    <input class='form-control' type="password" name="new_password" id="new_password" value="">
                </div>
            </div>
            <div class="form-group">
                <input type="hidden" name="page_next" value="edit_end">
                <div class="col-sm-10 col-sm-offset-2">
                    <button class='btn btn-primary' type="submit">Register</button> or
                    <a href="<?php eh(url(VIEW_USER_URL)); ?>">cancel</a>
                </div>
            </div>
        </form>
    </div>
</div>
