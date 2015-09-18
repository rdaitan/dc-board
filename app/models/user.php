<?php
class User extends AppModel
{
    const AUTH_SESS_KEY             = 'auth_user';
    const MIN_USERNAME_LENGTH       = 1;
    const MAX_USERNAME_LENGTH       = 16;
    const MIN_NAME_LENGTH           = 1;
    const MAX_NAME_LENGTH           = 30;
    const MIN_EMAIL_LENGTH          = 1;
    const MAX_EMAIL_LENGTH          = 30;
    const MIN_PASSWORD_LENGTH       = 6;
    const MAX_PASSWORD_LENGTH       = 20;
    const ERR_DUPLICATE_ENTRY       = 1062;
    const ERR_DUPLICATE_USERNAME    = 1;
    const ERR_DUPLICATE_EMAIL       = 2;
    const TABLE_NAME                = 'user';

    public $validation = array(
        'username'      => array(
            'length'    => array('validate_between', self::MIN_USERNAME_LENGTH, self::MAX_USERNAME_LENGTH),
            'chars'     => array('validate_username')
        ),
        'first_name'    => array(
            'length'    => array('validate_between', self::MIN_NAME_LENGTH, self::MAX_NAME_LENGTH),
            'chars'     => array('validate_name')
        ),
        'last_name'     => array(
            'length'    => array('validate_between', self::MIN_NAME_LENGTH, self::MAX_NAME_LENGTH),
            'chars'     => array('validate_name')
        ),
        'email'         => array(
            'length'    => array('validate_between', self::MIN_EMAIL_LENGTH, self::MAX_EMAIL_LENGTH),
            'chars'     => array('validate_email')
        ),
        'password'      => array(
            'length'    => array('validate_between', self::MIN_PASSWORD_LENGTH, self::MAX_PASSWORD_LENGTH))
    );

    public static function getOrFail($id)
    {
        $user = self::getById($id);

        if ($user) {
            return $user;
        } else {
            throw new RecordNotFoundException();
        }
    }

    public static function search($query, $offset, $limit)
    {
        $db = DB::conn();
        $rows = $db->rows(
            sprintf(
                "SELECT * FROM %s WHERE
                    username LIKE :query OR
                    first_name LIKE :query OR
                    last_name LIKE :query OR
                    email LIKE :query
                    LIMIT %d, %d",
                self::TABLE_NAME,
                $offset,
                $limit
            ),
            array('query' => "%{$query}%")
        );

        $search                 = new Search(get_called_class(), $rows);
        $search->total_result   = self::countResults($query);
        return $search;
    }

    public static function countResults($query)
    {
        $db = DB::conn();
        return $db->value(
            sprintf(
                "SELECT * FROM %s WHERE
                    username LIKE :query OR
                    first_name LIKE :query OR
                    last_name LIKE :query OR
                    email LIKE :query",
                self::TABLE_NAME
            ),
            array('query' => "%{$query}%")
        );
    }

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
                    'username'      => $this->username,
                    'first_name'    => $this->first_name,
                    'last_name'     => $this->last_name,
                    'email'         => $this->email,
                    'password'      => bhash($this->password)
                )
            );
        } catch (PDOException $e) {
            if ($e->errorInfo[1] != self::ERR_DUPLICATE_ENTRY) {
                return;
            }

            $duplicate_key_email    = "'email'";
            $duplicate_key_username = "'username'";

            if (substr($e->errorInfo[2], -strlen($duplicate_key_email)) == $duplicate_key_email) {
                throw new DuplicateEntryException(self::ERR_DUPLICATE_EMAIL);
            }

            if (substr($e->errorInfo[2], -strlen($duplicate_key_username)) == $duplicate_key_username) {
                throw new DuplicateEntryException(self::ERR_DUPLICATE_USERNAME);
            }
        }
    }

    public function update()
    {
        $change_password = !empty($this->current_password) || !empty($this->new_password);

        if ($change_password) {
            if (verify_hash($this->current_password, $this->password)) {
                $this->password = $this->new_password;  // must not be hashed before validation
            } else {
                $this->validation_errors['password']['match_old'] = true;
                throw new ValidationException();
            }
        }

        $this->validate();

        if (!$change_password) {
            unset ($this->validation_errors['password']);
        } else {
            $this->password = bhash($this->password);
        }

        if ($this->hasError()) {
            throw new ValidationException();
        }

        $db = DB::conn();
        $db->update(
            self::TABLE_NAME,
            array(
                'first_name'    => $this->first_name,
                'last_name'     => $this->last_name,
                'password'      => $this->password
            ),
            array('id' => $this->id)
        );
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

        $row = $db->row(sprintf('SELECT * FROM %s WHERE id=?', self::TABLE_NAME), array($id));

        return !$row ? false : new self($row);
    }

    public static function getByUsername($username)
    {
        $db = DB::conn();

        $row = $db->row(sprintf('SELECT * FROM %s WHERE username=?', self::TABLE_NAME), array($username));

        return !$row ? false : new self($row);
    }

    // Checks if the username and password matches a user in the database.
    // If there is a match, the user is logged in and the method returns true.
    // Otherwise, returns false.
    public static function authenticate($username, $password)
    {
        $user = User::getByUsername($username);

        if (!$user || !verify_hash($password, $user->password)) {
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
