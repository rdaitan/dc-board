<?php
# Authentication helper based on:
# http://snippetrepo.com/snippets/php-53-bcrypt-password-hash-the-proper-way

define('CRYPT_BFISH', '$2a$11$');

// returns the hashed string, using blowfish algorithm.
// blowfish algorithm is recommended by the php manual:
// http://php.net/manual/en/faq.string.php
function bhash($str, $salt = null)
{

    if(empty($salt)) {
        // the salt length for blowfish is only 22 characters long.
        // bin2hex() would produce 22 hex characters
        // 22 characters * 4 bits = 88 bits
        // openssl_random_pseudo_bytes() needs the number of bytes to generate.
        // 88 bits / 8 bits = 11 bytes
        $bitCount   = 11;
        $salt       = bin2hex(openssl_random_pseudo_bytes($bitCount));
    }

    return crypt($str, CRYPT_BFISH . $salt);
}
