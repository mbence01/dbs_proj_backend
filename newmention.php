<?php

session_start();
require_once("functions.php"); // TODO: Change path
require_once("db/models/Mention.php"); // TODO: Change path

const TEXT_MAX_LENGTH = 510;

if(!isUserLogged() or $_SERVER["REQUEST_METHOD"] != "POST")
    require_once("404.html"); // TODO: Change path

if(!isset($_POST["m_text"]) or strlen($_POST["m_text"]) < 1 or strlen($_POST["m_text"]) > TEXT_MAX_LENGTH)
    return showAlert("A komment hosszának 0 és " . TEXT_MAX_LENGTH . " közé kell esnie!", ALERT_TYPE_HISTORY_BACK);

if(!isset($_POST["v_id"]) or !isset($_POST["u_id"]) or !isset($_POST["m_parent"]))
    return showAlert("A megjegyzésküldő form módosítva lett, próbáld újra!");


require_once("db/models/Video.php");

$parentMentionInfo = array( "m_id" => array( $_POST["m_parent"], Model::REL_EQUALS ) );
$mentionVideoInfo = array( "v_id" => array( $_POST["v_id"], Model::REL_EQUALS ) );

if($_POST["m_parent"] != 0 and Mention::findAll($parentMentionInfo) == null)
    return showAlert("A megjegyzés, amire válaszolni szeretnél már nem létezik!", ALERT_TYPE_HISTORY_BACK);

if(Video::findAll($mentionVideoInfo) == null)
    return showAlert("A videó, amire reagálni szeretnél már nem létezik!", ALERT_TYPE_HISTORY_BACK);


$user = unserialize($_SESSION["userData"]);
$newMention = new Mention();

$newMention->setVId($_POST["v_id"]);
$newMention->setUId($user->getUId());
$newMention->setMText($_POST["m_text"]);
$newMention->setMParent($_POST["m_parent"]);

$result = Mention::addNew($newMention);

if(!$result)
    showAlert("Hiba történt a megjegyzésed rögzítése közben!", ALERT_TYPE_HISTORY_BACK);
else
    showAlert("Megjegyzésed rögzítésre került!", ALERT_TYPE_HISTORY_BACK);

?>