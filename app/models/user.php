<?php
class User extends AppModel
{
    const AUTH_USER_KEY = 'auth_user';

    public $validation = array(
        'username'      => array(
            'length'    => array('validate_between', 1, 16),
            'chars'     => array('validate_username'),
            'unique'    => array('validate_unique_name')
        ),
        'email'         => array(
            'length'    => array('validate_between', 1, 30),
            'chars'     => array('validate_email')
        ),
        'password'      => array(
            'length'    => array('validate_between', 6, 20))
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

    // Checks if the username and password matches a user in the database.
    // If there is a match, the user is logged in and the method returns true.
    // Otherwise, returns false.
    public static function authenticate($username, $password)
    {
        $db = DB::conn();

        // Get the user by username
        $row = $db->row('SELECT * FROM user WHERE username=?', array($username));
        if(empty($row)) return false;

        // Retrieve salt
        $user = new self($row);
        $salt = substr($user->password, strlen(CRYPT_BFISH), 22);   // BFISH salt length is 22

        $hashedPassword = bhash($password, $salt);
        $passwordsMatch = $hashedPassword === $user->password;

        if($passwordsMatch) {
            $user->setAuthUser();
            return true;
        } else {
            return false;
        }
    }

    private function setAuthUser()
    {
        $_SESSION[self::AUTH_USER_KEY] = $this->id;
    }

    // Returns the user that is authenticated via authenticate()
    public static function getAuthUser()
    {
        $id;

        if(array_key_exists(self::AUTH_USER_KEY, $_SESSION)) {
            $id = $_SESSION[self::AUTH_USER_KEY];
        } else {
            return false;
        }

        return User::get($id);
    }

    // Finds the user by id and returns a User object
    public static function get($id)
    {
        $db = DB::conn();

        // Get the user by id
        $row = $db->row('SELECT * FROM user WHERE id=?', array($id));
        if(empty($row)) return false;

        return new self($row);
    }

    public static function getByUsername($username)
    {
        $db = DB::conn();

        // Get the user by id
        $row = $db->row('SELECT * FROM user WHERE username=?', array($username));
        if(empty($row)) return false;

        return new self($row);
    }
}
