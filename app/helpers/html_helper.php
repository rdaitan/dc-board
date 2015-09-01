<?php

function eh($string)
{
    if (!isset($string)) return;
    echo htmlspecialchars($string, ENT_QUOTES);
}

function readable_text($s) {
    $s = htmlspecialchars($s, ENT_QUOTES);
    $s = nl2br($s);
    return $s;
}

// trim whitespace from $str and collapse adjacent whitespaces into one.
function trim_collapse($str) {
    $str = trim($str);
    $str = preg_replace('/[[:space:]]{2,}/', ' ', $str);
    return $str;
}

function redirect($str) {
    header("Location: " . url($str));
    die();
}

// Redirect the user if authenticated to the homepage or the specified url
function authRedirect($page = '/') {
    if(User::getAuthUser()) redirect($page);
}

// Redirects the user if not authenticated
function notAuthRedirect($page = '/') {
    if(!User::getAuthUser()) redirect($page);
}

function printPageLinks($pagination, $pages) {
    $page = Param::get('page', 1);

    echo '<nav><ul class="pagination">';

    if($pagination->current > 1) {
        $url = url('', array('page' => $pagination->prev));
        echo "<li><a href='{$url}'>&laquo;</a></li>";
    } else {
        echo "<li class='disabled'><a>&laquo;</a></li>";
    }

    for($i = 1; $i <= $pages; $i++) {
        if($i == $page) {
            echo "<li class='disabled'><a>{$i}</a></li>";
        } else {
            $url = url('', array('page' => $i));
            echo "<li><a href='{$url}'>{$i}</a></li>";
        }
    }

    if(!$pagination->is_last_page) {
        $url = url('', array('page' => $pagination->next));
        echo "<li><a href='{$url}'>&raquo;</a></li>";
    } else {
        echo "<li class='disabled'><a>&raquo;</a></li>";
    }

    echo '</ul></nav>';
}
