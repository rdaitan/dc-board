<?php
function validate_between($str, $min, $max) {
    $length = mb_strlen($str); // count characters based on encoding
    return $length <= $max && $length >= $min;
}

function validate_name($str) {
    return preg_match('/^[[:alpha:] ]*$/', $str);
}

function validate_username($str) {
    return preg_match('/^[[:alnum:]_]*$/', $str);
}

function validate_uniqueness($username, $table, $column) {
    $db = DB::conn();

    $row    = $db->row("SELECT * FROM {$table} WHERE {$column}=?", array($username));
    // table names can't have single quotes. ? surrounds the value
    // with single quotes.
    // $column can't be surrounded with single quotes as it would look like we're
    // comparing strings.

    return empty($row);
}

function validate_email($str) {
    return filter_var($str, FILTER_VALIDATE_EMAIL);
}
