<?php

const ALERT_TYPE_HISTORY_BACK = 0;
const ALERT_TYPE_STAY_HERE = 1;

function showAlert($message, $path) {
    $location = "'" . $path . "'";

    if($path == ALERT_TYPE_HISTORY_BACK)
        $location = "history.back()";
    if($path == ALERT_TYPE_STAY_HERE)
        $location = "window.location.pathname";

    echo "
            <script type='text/javascript'>
                window.alert('" . $message . "');
                window.location = " . $location . ";
            </script>
         ";
}

function isUserLogged(): bool {
    global $_SESSION;

    return isset($_SESSION["logged"]) and $_SESSION["logged"];
}

?>