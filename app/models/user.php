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
            'password'  => bhash($this->password)
        );

        $db = DB::conn();
        $db->insert('user', $info);
    }

    public static function authenticate($username, $password) {
        $db = DB::conn();

        // Get the user by username
        $row = $db->row('SELECT * FROM user WHERE username=?', array($username));
        if(empty($row)) return false;

        // Retrieve salt
        $user = new self($row);
        $salt = substr($user->password, strlen(CRYPT_BFISH), 22);   // BFISH salt length is 22

        $hashedPassword = bhash($password, $salt);
        return $hashedPassword === $user->password;
    }

    private function setAuthUser() {
        $_SESSION['auth_user'] = $this->id;
    }

    public static function get($id) {
        $db = DB::conn();

        // Get the user by id
        $row = $db->row('SELECT * FROM user WHERE id=?', array($id));
        if(empty($row)) return false;

        return new self($row);
    }
}
