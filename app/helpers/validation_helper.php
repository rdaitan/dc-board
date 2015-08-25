<?php
function validate_between($str, $min, $max) {
    $length = mb_strlen($str); // count characters based on encoding
    return $length <= max && $length >= $min;
}
