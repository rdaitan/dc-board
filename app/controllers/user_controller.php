<?php
class UserController extends AppController
{
    const ERROR_VAR                 = 'error';
    const ERROR_MESSAGE_USERPASS    = 'Incorrect username or password';

    public function create()
    {
        redirect_auth_user();

        $user = new User();
        $page = Param::get('page_next', 'create');

        switch ($page) {
        case 'create_end':
            $user->username = trim(Param::get('username'));
            $user->email    = trim(Param::get('email'));
            $user->password = Param::get('password');

            try {
                $user->create();
            } catch(ValidationException $e) {
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
                redirect(APP_URL);;
            } else {
                $this->set(array(self::ERROR_VAR => self::ERROR_MESSAGE_USERPASS));
                $page = 'auth';
            }
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
}
