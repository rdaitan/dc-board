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

    public function authenticate()
    {
        $page = Param::get('page_next', 'auth');

        switch($page) {
            case 'auth_end':
                $username = Param::get('username');
                $password = Param::get('password');

                try {
                    if(User::authenticate($username, $password)) {
                        echo ("YAY");
                    } else {
                        echo "BOO BOO";
                    }
                } catch(RecordNotFoundException $e) {
                    // TODO: show login form
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
