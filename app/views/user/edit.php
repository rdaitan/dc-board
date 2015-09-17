
<div class="row">
    <h3>Edit profile</h3>
</div>
<div class="row">
    <?php if ($auth_user->hasError()): ?>
        <div class="red">
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
</div>
<div class="row">
    <!--Edit profile form-->
    <form action="<?php eh(url('')); ?>" method="post">
        <div class="row">
            <div class="six columns">
                <label for="first_name">First name</label>
                <input class='u-full-width' type="text" name="first_name" id="first_name" value="<?php eh($auth_user->first_name); ?>">
            </div>
            <div class="six columns">
                <label for="last_name">Last name</label>
                <input class='u-full-width' type="text" name="last_name" id="last_name" value="<?php eh($auth_user->last_name); ?>">
            </div>
        </div>
        <div class="row">
            <div class="six columns">
                <label for="password">Password</label>
                <input class='u-full-width' type="password" name="password" id="password" value="">
            </div>
            <div class="six columns">
                <label for="new_password">New Password</label>
                <input class='u-full-width' type="password" name="new_password" id="new_password" value="">
            </div>
        </div>
        <div class="row">
            <input type="hidden" name="page_next" value="edit_end">
            <button class='btn btn-primary' type="submit">Save</button> or
            <a href="<?php eh(url(VIEW_USER_URL)); ?>">cancel</a>
        </div>
    </form>
</div>
