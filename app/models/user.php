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
            'password'  => hash_password($this->password)
        );

        $db = DB::conn();
        $db->insert('user', $info);
    }

    public static function authenticate($username, $password) {
        $db = DB::conn();

        // Get the user by username
        $row = $db->row('SELECT * FROM user WHERE username=?', array($username));
        if(empty($row)) {
            throw new RecordNotFoundExceptioni('No user found.');
        }

        // Retrieve salt
        $user = new self($row);
        $salt = substr($user->password, length(CRYPT_BFISH), 22);   // BFISH salt length is 22

        $hashedPassword = hash_password($password);
        return $hashedPassword === $user->password;
    }
}
