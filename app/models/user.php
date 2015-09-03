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
        if (!$this->validate()) {
            throw new ValidationException('Invalid user information');
        }

        $db = DB::conn();
        $db->insert('user', array(
            'username'  => $this->username,
            'email'     => $this->email,
            'password'  => bhash($this->password))
        );
    }

    // Checks if the username and password matches a user in the database.
    // If there is a match, the user is logged in and the method returns true.
    // Otherwise, returns false.
    public static function authenticate($username, $password)
    {
        $user = User::getByUsername($username);

        // Retrieve salt
        $salt = substr($user->password, strlen(CRYPT_BFISH), 22);   // BFISH salt length is 22

        $hashedPassword = bhash($password, $salt);

        if ($hashedPassword !== $user->password) {
            return false;
        }

        $user->setAuthenticated();
        return true;
    }

    private function setAuthenticated()
    {
        $_SESSION[self::AUTH_USER_KEY] = $this->id;
    }

    // Returns the user that is authenticated via authenticate()
    public static function getAuthenticated()
    {
        if (!array_key_exists(self::AUTH_USER_KEY, $_SESSION)) {
            return false;
        }

        return User::getById($_SESSION[self::AUTH_USER_KEY]);
    }

    // Finds the user by id and returns a User object
    public static function getById($id)
    {
        $db = DB::conn();

        $row = $db->row('SELECT * FROM user WHERE id=?', array($id));

        return !$row ? false : new self($row);
    }

    public static function getByUsername($username)
    {
        $db = DB::conn();

        $row = $db->row('SELECT * FROM user WHERE username=?', array($username));

        return !$row ? false : new self($row);
    }
}
