<?php
# Authentication helper based on:
# http://snippetrepo.com/snippets/php-53-bcrypt-password-hash-the-proper-way

define('CRYPT_BFISH', '$2a$11$');
define('BFISH_SALT_LENGTH',   22);
define('BFISH_RAND_BIT_COUNT', 11);

// returns the hashed string, using blowfish algorithm.
// blowfish algorithm is recommended by the php manual:
// http://php.net/manual/en/faq.string.php
function bhash($str, $salt = null)
{

    if (!$salt) {
        // the salt length for blowfish is only 22 characters long.
        // bin2hex() would produce 22 hex characters
        // 22 characters * 4 bits = 88 bits
        // openssl_random_pseudo_bytes() needs the number of bytes to generate.
        // 88 bits / 8 bits = 11 bytes
        $salt       = bin2hex(openssl_random_pseudo_bytes(BFISH_RAND_BIT_COUNT));
    }

    return crypt($str, CRYPT_BFISH . $salt);
}
