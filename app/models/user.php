<?php
class User extends AppModel
{
    public $validation = array(
        'username'  => array('length' => array('validate_between', 1, 16)),
        'email'     => array('length' => array('validate_between', 1, 30)),
        'password'  => array('length' => array('validate_between', 6, 20))
    );

    public function create()
    {
        if(!$this->validate()) {
            throw new ValidationException('Invalid user information');
        }

        $info = array(
            'username'  => $this->username,
            'email'     => $this->email,
            'password'  => $this->password
        );

        $db = DB::conn();
        $db->insert('user', $info);
    }
}
