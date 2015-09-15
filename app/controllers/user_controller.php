<?php
class UserController extends AppController
{
    const ERROR_VAR                 = 'error';
    const ERROR_MESSAGE_USERPASS    = 'Incorrect username or password';
    const PROFILE_THREAD_LIMIT      = 5;
    const PROFILE_COMMENT_LIMIT     = 5;

    public function create()
    {
        redirect_auth_user();

        $user = new User();
        $page = Param::get('page_next', 'create');

        switch ($page) {
        case 'create_end':
            $user->username     = trim(Param::get('username'));
            $user->first_name   = trim_collapse(Param::get('first_name'));
            $user->last_name    = trim_collapse(Param::get('last_name'));
            $user->email        = trim(Param::get('email'));
            $user->password     = Param::get('password');

            try {
                $user->create();
            } catch (ValidationException $e) {
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
        redirect(APP_URL);;
    }

    public function view()
    {
        $id = Param::get('id');

        if($id) {
            $user = User::getOrFail($id);
        } else {
            $user = User::getAuthenticated();

            if (!$user) {
                throw new RecordNotFoundException();
            }
        }

        $threads = Thread::getAll(0, self::PROFILE_THREAD_LIMIT, array('user_id' => $user->id));
        $comments = Comment::getAll(0, self::PROFILE_COMMENT_LIMIT, array('user_id' => $user->id));

        $this->set(get_defined_vars());
    }
}
