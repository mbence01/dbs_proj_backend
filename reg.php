<?php

session_start();
require_once("functions.php"); // TODO: Change path

const USERNAME_MAX_LENGTH   = 32;
const EMAIL_MAX_LENGTH      = 64;
const PASSWORD_MIN_LENGTH   = 6;
const PROFILE_MAX_LENGTH    = 5000000;

if(isUserLogged())
    return header("Location: index.php");
    // TODO: Change index path


if($_SERVER["REQUEST_METHOD"] != "POST")
    return require_once("404.html"); // TODO: Change path


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
        return showAlert($msg, ALERT_TYPE_HISTORY_BACK);
    }
}


/**
 * Check if fields' length are incorrect + password == password2?
 */
if(strlen($_POST["u_username"]) > USERNAME_MAX_LENGTH)
    return showAlert(
        "A felhasználónév nem lehet hosszabb " . USERNAME_MAX_LENGTH . " karakternél!",
        ALERT_TYPE_HISTORY_BACK);

if(strlen($_POST["u_email"]) > EMAIL_MAX_LENGTH)
    return showAlert(
        "Az e-mail cím nem lehet hosszabb " . EMAIL_MAX_LENGTH . " karakternél!",
        ALERT_TYPE_HISTORY_BACK);

if(strlen($_POST["u_password"]) < PASSWORD_MIN_LENGTH)
    return showAlert(
        "A jelszó mezőnek legalább " . PASSWORD_MIN_LENGTH . " karakterből kell állnia!",
        ALERT_TYPE_HISTORY_BACK);

if($_POST["u_password"] != $_POST["u_password2"])
    return showAlert("A megadott jelszavak nem egyeznek!", ALERT_TYPE_HISTORY_BACK);



/**
 * Check if unique fields are reserved.
 */
require_once("db/models/Account.php");

$userArr  = array(
                    "u_username" => array($_POST["u_username"], Account::REL_EQUALS)
                 );

$emailArr  = array(
                    "u_email" => array($_POST["u_email"], Account::REL_EQUALS)
                  );

if(Account::existsWith($userArr))
    return showAlert("A megadott felhasználónév már regisztrálva van!", ALERT_TYPE_HISTORY_BACK);

if(Account::existsWith($emailArr))
    return showAlert("A megadott e-mail cím már regisztrálva van!", ALERT_TYPE_HISTORY_BACK);


/**
 * Password encryption, profile picture upload and finally insert the data to the database
 */
$pass = md5($_POST["u_password"]);
$profilePicture = "blank_pfp.png";

if(isset($_FILES["u_profile"]) and $_FILES["u_profile"]["size"] > 0) {
    $file_ext = strtolower(pathinfo($_FILES["u_profile"]["name"], PATHINFO_EXTENSION));
    $accepted_exts = array( "jpg", "png", "jpeg", "ico" );

    if(!in_array($file_ext, $accepted_exts))
        return showAlert("A megadott fájlformátum nem támogatott!", ALERT_TYPE_HISTORY_BACK);

    if($_FILES["u_profile"]["size"] > PROFILE_MAX_LENGTH)
        return showAlert("A megadott fájl mérete túl nagy! (Max. " . PROFILE_MAX_LENGTH/1000000 . " mb)", ALERT_TYPE_HISTORY_BACK);

    if(move_uploaded_file($_FILES["u_profile"]["tmp_name"], PROFILE_DIRECTORY . $_FILES["u_profile"]["name"]))
        $profilePicture = PROFILE_DIRECTORY . $_FILES["u_profile"]["name"];
}


$userInfo = array(
    "u_username"    => $_POST["u_username"],
    "u_email"       => $_POST["u_email"],
    "u_password"    => $pass,
    "u_public"      => isset($_POST["u_public"]) ? 1 : 0,
    "u_born_date"   => $_POST["u_born_date"],
    "u_profile"     => $profilePicture
);
$newAcc = Account::fromArray($userInfo);

$result = Account::addNew($newAcc);

if(!$result)
    showAlert("Ismeretlen hiba történt! Próbáld meg újra pár perc múlva!", ALERT_TYPE_HISTORY_BACK);
else
    showAlert("Sikeresen regisztráltál!", "login.html"); // TODO: Change path

?>