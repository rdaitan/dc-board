<?php

function eh($string)
{
    if (!isset($string)) return;
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

function redirect($str)
{
    header("Location: " . url($str));
    die();
}

// Redirect the user if authenticated to the homepage or the specified url
function redirect_auth_user($page = APP_URL)
{
    if (User::getAuthenticated()) redirect($page);
}

// Redirects the user if not authenticated
function redirect_guest_user($page = APP_URL)
{
    if (!User::getAuthenticated()) redirect($page);
}

function printPageLinks($pagination, $pages)
{
    $page = Param::get('page', 1);

    echo '<nav><ul class="pagination">';

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

    echo '</ul></nav>';
}
