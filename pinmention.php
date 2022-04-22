<?php

session_start();
require_once("functions.php"); // TODO: Change path
require_once("db/models/Mention.php"); // TODO: Change path

if(!isUserLogged())
    header("Location: login.html"); // TODO: Change path

if(!isset($_GET["id"]))
    return header("Location: index.php"); // TODO: Change path


require_once("db/models/Account.php"); // TODO: Change path
require_once("db/models/Video.php"); // TODO: Change path

$user = unserialize($_SESSION["userData"]);
$getMention = Mention::findAll(array( "m_id" => array( $_GET["id"], Model::REL_EQUALS ) ));

if($getMention == null)
    return showAlert("Hiba történt a megjegyzés módosítása közben!\nA megadott megjegyzésazonosító nem létezik!", ALERT_TYPE_HISTORY_BACK);


foreach($getMention as $mention) {
    $chVideoQueryInfo = array(
        "v_id" => array( $mention->getVId(), Model::REL_EQUALS ),
        "v_uploader_id" => array( $user->getUId(), Model::REL_EQUALS )
    );

    $checkVideoQuery = Video::findAll($chVideoQueryInfo);

    if($checkVideoQuery == null)
        return showAlert("Hiba történt a megjegyzés módosítása közben!\nEz a megjegyzés nem emelhető ki!", ALERT_TYPE_HISTORY_BACK);

    $mention->setMPinned(($mention->getMPinned() == 0) ? 1 : 0);

    if($mention->update())
        showAlert("Megjegyzés módosítva!", ALERT_TYPE_HISTORY_BACK);
    else
        showAlert("Hiba történt a megjegyzés módosítása során!\nIsmeretlen hiba", ALERT_TYPE_HISTORY_BACK);
    break;
}

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
$newMention->setMParent(($_POST["m_parent"] == 0) ? null : $_POST["m_parent"]);

$result = Mention::addNew($newMention);

if(!$result)
    showAlert("Hiba történt a megjegyzésed rögzítése közben!", ALERT_TYPE_HISTORY_BACK);
else
    showAlert("Megjegyzésed rögzítésre került!", ALERT_TYPE_HISTORY_BACK);

?>