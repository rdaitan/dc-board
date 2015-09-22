<?php
function validate_between($str, $min, $max)
{
    $length = mb_strlen($str); // count characters based on encoding
    return $length <= $max && $length >= $min;
}

function validate_username($str)
{
    return preg_match('/^[[:alnum:]_]*$/', $str);
}

function validate_name($str)
{
    return preg_match('/^[[:alpha:] ]*$/', $str);
}

function validate_unique_name($username)
{
    return !User::getByUsername($username);
}

function validate_email($str)
{
    return filter_var($str, FILTER_VALIDATE_EMAIL);
}
