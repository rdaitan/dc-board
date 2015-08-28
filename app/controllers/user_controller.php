<?php
class UserController extends AppController
{
    public function create() {
        $user = new User;
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
                throw new NotFoundException("{$page} is not found.");
                break;
        }

        $this->set(get_defined_vars());
        $this->render($page);
    }

    const ERROR_VAR                 = 'error';
    const ERROR_MESSAGE_USERPASS    = 'Incorrect username or password';

    public function authenticate()
    {
        $page = Param::get('page_next', 'auth');

        switch($page) {
            case 'auth_end':
                $username = Param::get('username');
                $password = Param::get('password');

                if(User::authenticate($username, $password)) {
                    redirect('/thread/index');
                } else {
                    $this->set(array(self::ERROR_VAR => self::ERROR_MESSAGE_USERPASS));
                    $page = 'auth';
                }
                break;
            case 'auth':
                break;
            default:
                throw new NotFoundException("{$page} not found.");
        }

        $this->render($page);
    }
}
