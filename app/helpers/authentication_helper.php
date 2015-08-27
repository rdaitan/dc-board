<?php
# Authentication helpers based on:
# http://snippetrepo.com/snippets/php-53-bcrypt-password-hash-the-proper-way

define('CRYPT_BFISH', '$2a$11$');

// returns the hashed password, using blowfish alorithm.
// blowfish algorithm is recommended by the php manual:
// http://php.net/manual/en/faq.passwords.php
function hash_password($password)
{

    // the salt is only 22 characters long.
    // openssl_random_pseudo_bytes() would generate 88 bits.
    // bin2hex() would produce 22 hex characters (88/4).
    $salt = bin2hex(openssl_random_pseudo_bytes(11));

    return crypt($password, CRYPT_BFISH . $salt);
}
