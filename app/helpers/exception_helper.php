<?php
define('ERROR_404', 'HTTP/1.0 404');
define('ERROR_403', 'HTTP/1.0 403');
define('ERROR_500', 'HTTP/1.0 500');
define('MESSAGE_404', 'THAT ITEM MIGHT NOT EXIST.');
define('MESSAGE_403', "YOU DON'T HAVE PERMISSION TO DO THAT.");
define('MESSAGE_500', 'SOMETHING BAD HAPPENED.');

set_exception_handler(
    function (Exception $exception) {
        if ($exception instanceof RecordNotFoundException) {
            display_message(ERROR_404, MESSAGE_404, $exception);
        }

        if ($exception instanceof PermissionException) {
            display_message(ERROR_403, MESSAGE_403, $exception);
        }

        display_message(ERROR_500, MESSAGE_500, $exception);
    }
);

function display_message($status_code, $message, $exception) {
    header($status_code);

    if (!ENV_PRODUCTION) {
        var_dump($exception);
    }

    print($message);
    die();
}
