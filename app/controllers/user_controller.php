<?php
class UserController extends AppController
{
    const ERROR_VAR                 = 'error';
    const ERROR_MESSAGE_USERPASS    = 'Incorrect username or password';
    const ERROR_PASS_NOT_MATCH      = 1;

    public function create()
    {
        redirect_auth_user();

        $user = new User();
        $page = Param::get('page_next', 'create');

        switch ($page) {
        case 'create_end':
            $user->username         = trim(Param::get('username'));
            $user->first_name       = trim_collapse(Param::get('first_name'));
            $user->last_name        = trim_collapse(Param::get('last_name'));
            $user->email            = trim(Param::get('email'));
            $user->password         = Param::get('password');
            $user->password_confirm = Param::get('password_confirm');

            try {
                if ($user->password_confirm != $user->password) {
                    throw new ValidationException (self::ERROR_PASS_NOT_MATCH);
                }
                $user->create();
            } catch (ValidationException $e) {
                if ($e->getMessage() == self::ERROR_PASS_NOT_MATCH) {
                    $user->validation_errors['password_confirm']['match'] = true;
                }
                $page = 'create';
            } catch (DuplicateEntryException $e) {
                switch ($e->getMessage()) {
                case User::ERR_DUPLICATE_USERNAME:
                    $user->validation_errors['username']['unique'] = true;
                    break;
                case User::ERR_DUPLICATE_EMAIL:
                    $user->validation_errors['email']['unique'] = true;
                    break;
                default:
                    break;
                }
                $page = 'create';
            }
            break;
        case 'create':
            break;
        default:
            throw new PageNotFoundException("{$page} is not found.");
            break;
        }

        $title = 'Create User';
        $this->set(get_defined_vars());
        $this->render($page);
    }

    public function authenticate()
    {
        redirect_auth_user();

        $page = Param::get('page_next', 'auth');

        switch ($page) {
        case 'auth_end':
            $username = Param::get('username');
            $password = Param::get('password');

            if (User::authenticate($username, $password)) {
                redirect(APP_URL);
            }

            $this->set(array(self::ERROR_VAR => self::ERROR_MESSAGE_USERPASS));
            $page = 'auth';
            break;
        case 'auth':
            break;
        default:
            throw new PageNotFoundException("{$page} not found.");
        }

        $title = 'Log in';
        $this->set(get_defined_vars());
        $this->render($page);
    }

    public function logout()
    {
        if (User::getAuthenticated()) {
            session_destroy();
        }
        redirect(APP_URL);
    }

    public function view()
    {
        $id         = Param::get('id');
        $auth_user  = User::getAuthenticated();

        if ($id) {
            $user = User::getOrFail($id);
        } elseif ($auth_user) {
            $user = $auth_user;
        } else {
            throw new RecordNotFoundException();
        }

        $threads    = Thread::getAllByUser($user);
        $comments   = Comment::getAllByUser($user);
        $follows    = Follow::getAll($user);

        foreach ($follows as $follow_key => $follow_element) {
            $thread = Thread::get($follow_element->thread_id);
            $follow_element->thread_title = $thread->title;
        }

        $title = $user->username;
        $this->set(get_defined_vars());
    }

    public function edit()
    {
        redirect_guest_user(LOGIN_URL);

        $page = Param::get('page_next', 'edit');
        $auth_user = User::getAuthenticated();

        switch($page) {
        case 'edit':
            break;
        case 'edit_end':
            $auth_user->first_name = trim_collapse(Param::get('first_name'));
            $auth_user->last_name = trim_collapse(Param::get('last_name'));
            $auth_user->old_password = Param::get('password');
            $auth_user->new_password = Param::get('new_password');

            try {
                $auth_user->change_password = $auth_user->old_password || $auth_user->new_password;

                if ($auth_user->change_password) {
                    if (verify_hash($auth_user->old_password, $auth_user->password)) {
                        $auth_user->password = $auth_user->new_password;
                    } else {
                        throw new ValidationException();
                    }
                }

                $auth_user->update();
                redirect(VIEW_USER_URL);
            } catch (ValidationException $e) {
                $auth_user->validation_errors['password']['match_old'] = $auth_user->change_password;
                $page = 'edit';
                break;
            }
            break;
        default:
            throw new PageNotFoundException();
            break;
        }

        $title = 'Edit Profile';
        $this->set(get_defined_vars());
    }
}
