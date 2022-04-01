<?php

session_start();


const USERNAME_MAX_LENGTH   = 32;
const EMAIL_MAX_LENGTH      = 64;
const PASSWORD_MIN_LENGTH   = 6;
const PROFILE_MAX_LENGTH    = 5000000;


if(isset($_SESSION["logged"]) AND $_SESSION["logged"])
    header("Location: index.php");
    // TODO: Change index path


if($_SERVER["REQUEST_METHOD"] != "POST")
    require_once("404.html"); // TODO: Change path


/**
 * Check if required fields are empty.
 */
require_once("functions.php"); // TODO: Change path

$required_fields = array(
    "u_username"    =>  "felhasználónév",
    "u_email"       =>  "e-mail cím",
    "u_password"    =>  "jelszó",
    "u_password2"   =>  "jelszó megerősítése",
    "u_born_date"   =>  "születési dátum"
);


foreach($required_fields as $key => $value) {
    if(!isset($_POST[$key]) or empty($_POST[$key])) {
        $msg = "A(z) " . $value . " mező kitöltése kötelező!";
        showAlert($msg, ALERT_TYPE_HISTORY_BACK);
    }
}


/**
 * Check if fields' length are incorrect + password == password2?
 */
if(strlen($_POST["u_username"]) > USERNAME_MAX_LENGTH)
    showAlert(
        "A felhasználónév nem lehet hosszabb " . USERNAME_MAX_LENGTH . " karakternél!",
        ALERT_TYPE_HISTORY_BACK);

if(strlen($_POST["u_email"]) > EMAIL_MAX_LENGTH)
    showAlert(
        "A felhasználónév nem lehet hosszabb " . EMAIL_MAX_LENGTH . " karakternél!",
        ALERT_TYPE_HISTORY_BACK);

if(strlen($_POST["u_password"]) < PASSWORD_MIN_LENGTH)
    showAlert(
        "A jelszó mezőnek legalább " . PASSWORD_MIN_LENGTH . " karakterből kell állnia!",
        ALERT_TYPE_HISTORY_BACK);

if($_POST["u_password"] != $_POST["u_password2"])
    showAlert("A megadott jelszavak nem egyeznek!", ALERT_TYPE_HISTORY_BACK);



/**
 * Check if unique fields are reserved.
 */
require_once("db/models/Account.php");

if(Account::existsWith("u_username", $_POST["u_username"]))
    showAlert($msg, "A megadott felhasználónév már regisztrálva van!", ALERT_TYPE_HISTORY_BACK);

if(Account::existsWith("u_email", $_POST["u_email"]))
    showAlert($msg, "A megadott e-mail cím már regisztrálva van!", ALERT_TYPE_HISTORY_BACK);


/**
 * Password encryption, profile picture upload and finally insert the data to the database
 */
$pass = md5($_POST["password"]);
$file_content = "";

if(isset($_FILES["u_profile"])) {
    $file_ext = strtolower(pathinfo($_FILES["u_profile"]["name"], PATHINFO_EXTENSION));
    $accepted_exts = array( "jpg", "png", "jpeg" );

    if(!in_array($file_ext, $accepted_exts))
        showAlert("A megadott fájlformátum nem támogatott!", ALERT_TYPE_HISTORY_BACK);

    if($_FILES["u_profile"]["size"] > PROFILE_MAX_LENGTH)
        showAlert("A megadott fájl mérete túl nagy! (Max. " . PROFILE_MAX_LENGTH/1000000 . " mb)", ALERT_TYPE_HISTORY_BACK);

    $file_content = addslashes(file_get_contents($_FILES["u_profile"]["tmp_name"]));
    unlink($_FILES["u_profile"]["tmp_name"]);
} else {
    $file_content = addslashes(file_get_contents("blank_pfp.png")); // TODO: Change def. img. path
}

$result = Account::addNew(  $_POST["u_username"],
                            $_POST["u_email"],
                            $pass,
                            isset($_POST["u_public"]) ? 1 : 0,
                            $_POST["u_born_date"],
                            $file_content
);

if(!$result)
    showAlert("Ismeretlen hiba történt! Kérlek próbáld meg újra pár perc múlva!", ALERT_TYPE_HISTORY_BACK);
else
    showAlert("Sikeresen regisztráltál!", "login.html"); // TODO: Change path

?>