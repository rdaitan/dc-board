<?php
class UserController extends AppController
{
    public function create() {
        $user = new User;
        $page = Param::get('page_next');

        switch ($page) {
            case 'create_end':
                $user->username = trim(Param::get('username'));
                $user->email    = trim(Param::get('email'));
                $user->password = Param::get('password'); // TODO: hash

                try {
                    $user->create();
                } catch(ValidationException $e) {
                    $page = 'create';
                }
                break;
            default:
                throw new NotFoundException("{$page} is not found.");
                break;
        }

        $this->set(get_defined_vars());
        $this->render($page);
    }
}
