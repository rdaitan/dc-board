<?php

function eh($string)
{
    if (!isset($string)) {
        return;
    }

    echo htmlspecialchars($string, ENT_QUOTES);
}

function readable_text($string)
{
    $string = htmlspecialchars($string, ENT_QUOTES);
    $string = nl2br($string);
    return $string;
}

// trim whitespace from $str and collapse adjacent whitespaces into one.
function trim_collapse($str)
{
    $str = trim($str);
    $str = preg_replace('/[[:space:]]{2,}/', ' ', $str);
    return $str;
}

function redirect($url, $query)
{
    header("Location: " . url($url, $query));
    die();
}

// Redirect the user if authenticated to the homepage or the specified url
function redirect_auth_user($url = APP_URL)
{
    if (User::getAuthenticated()) {
        redirect($url);
    }
}

// Redirects the user if not authenticated
function redirect_guest_user($url = APP_URL)
{
    if (!User::getAuthenticated()) {
        redirect($url);
    }
}

function print_pagination($pagination, $pages)
{
    $page = Param::get('page', 1);

    echo '<ul class="pagination">';

    // previous button
    if ($pagination->current > 1) {
        $url = url('', array('page' => $pagination->prev));
        echo "<li><a href='{$url}'>&laquo;</a></li>";
    } else {
        echo "<li class='disabled'><a>&laquo;</a></li>";
    }

    // page numbers
    for ($i = 1; $i <= $pages; $i++) {
        if ($i == $page) {
            echo "<li class='disabled'><a>{$i}</a></li>";
        } else {
            $url = url('', array('page' => $i));
            echo "<li><a href='{$url}'>{$i}</a></li>";
        }
    }

    // next button
    if ($pagination->is_last_page) {
        echo "<li class='disabled'><a>&raquo;</a></li>";
    } else {
        $url = url('', array('page' => $pagination->next));
        echo "<li><a href='{$url}'>&raquo;</a></li>";
    }

    echo '</ul>';
}

function verify_hash($str, $hash)
{
    // Retrieve salt
    $salt = substr($hash, strlen(CRYPT_BFISH), BFISH_SALT_LENGTH);

    $hashedPassword = bhash($str, $salt);

    return $hashedPassword === $hash;
}

function send_json($json) {
    header('Content-type: application/json; charset=utf-8');
    echo json_encode($json);
    die();
}
