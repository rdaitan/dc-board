<?php

set_exception_handler(
    function (Exception $exception) {
        var_dump($exception);

        if ($exception instanceof RecordNotFoundException) {
            echo 'RECORD NOT FOUND!';
        }

        if ($exception instanceof PermissionException) {
            echo "YOU DON'T HAVE PERMISSION TO DO THAT!";
        }
    }
);
