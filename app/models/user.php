<?php
class User extends AppModel
{
    const AUTH_SESS_KEY         = 'auth_user';
    const MIN_USERNAME_LENGTH   = 1;
    const MAX_USERNAME_LENGTH   = 16;
    const MIN_EMAIL_LENGTH      = 1;
    const MAX_EMAIL_LENGTH      = 30;
    const MIN_PASSWORD_LENGTH   = 6;
    const MAX_PASSWORD_LENGTH   = 20;
    const ERR_DUPLICATE_ENTRY   = 1062;

    public $validation = array(
        'username'      => array(
            'length'    => array('validate_between', self::MIN_USERNAME_LENGTH, self::MAX_USERNAME_LENGTH),
            'chars'     => array('validate_username'),
            // 'unique'    => array('validate_unique_name')
        ),
        'email'         => array(
            'length'    => array('validate_between', self::MIN_EMAIL_LENGTH, self::MAX_EMAIL_LENGTH),
            'chars'     => array('validate_email')
        ),
        'password'      => array(
            'length'    => array('validate_between', self::MIN_PASSWORD_LENGTH, self::MAX_PASSWORD_LENGTH))
    );

    public function create()
    {
        if (!$this->validate()) {
            throw new ValidationException('Invalid user information');
        }

        $db = DB::conn();
        try {
            $db->insert(
                'user',
                array(
                    'username'  => $this->username,
                    'email'     => $this->email,
                    'password'  => bhash($this->password)
                )
            );
        } catch (PDOException $e) {
            if ($e->errorInfo[1] == self::ERR_DUPLICATE_ENTRY) {
                throw new DuplicateEntryException('Duplicate username');
            }
        }
    }

    // Returns the user that is authenticated via authenticate()
    public static function getAuthenticated()
    {
        if (!array_key_exists(self::AUTH_SESS_KEY, $_SESSION)) {
            return false;
        }

        return User::getById($_SESSION[self::AUTH_SESS_KEY]);
    }

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

    // Checks if the username and password matches a user in the database.
    // If there is a match, the user is logged in and the method returns true.
    // Otherwise, returns false.
    public static function authenticate($username, $password)
    {
        $user = User::getByUsername($username);

        // Retrieve salt
        $salt = substr(
            $user->password,
            strlen(CRYPT_BFISH),
            BFISH_SALT_LENGTH
        );

        $hashedPassword = bhash($password, $salt);

        if ($hashedPassword !== $user->password) {
            return false;
        }

        $user->setAuthenticated();
        return true;
    }

    private function setAuthenticated()
    {
        $_SESSION[self::AUTH_SESS_KEY] = $this->id;
    }
}
