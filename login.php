<?php

session_start();
require_once("functions.php"); // TODO: Change path

if(isUserLogged())
    header("Location: index.php");
    // TODO: Change index path


if($_SERVER["REQUEST_METHOD"] != "POST")
    require_once("404.html"); // TODO: Change path


$required_fields = array(
    "u_username"    =>  "felhasználónév",
    "u_password"    =>  "jelszó"
);


foreach($required_fields as $key => $value) {
    if(!isset($_POST[$key]) or empty($_POST[$key])) {
        $msg = "A(z) " . $value . " mező kitöltése kötelező!";
        showAlert($msg, ALERT_TYPE_HISTORY_BACK);
    }
}


require_once("db/models/Account.php");

$loginCreds  = array(
    "u_username" => array($_POST["u_username"],      Account::REL_EQUALS),
    "u_password" => array(md5($_POST["u_password"]), Account::REL_EQUALS)
);

if(!Account::existsWith($loginCreds))
    showAlert("Hibás felhasználónév vagy jelszó!", ALERT_TYPE_HISTORY_BACK);

showAlert("Sikeres bejelentkezés!", "index.php");

?>