<?php

session_start();
require_once("functions.php"); // TODO: Change path
require_once("db/models/Video.php"); // TODO: Change path

const VIDEO_MAX_SIZE    = 500000000;
const VIDEO_TARGET_DIR  = "videostorage/"; // TODO: Change target dir
const ACCEPTABLE_TYPES  = array( "mp4", "avi", "mov", "mkv" );

if(!isUserLogged() or $_SERVER["REQUEST_METHOD"] != "POST")
    require_once("404.html"); // TODO: Change path

if(empty($_POST["v_title"]))
    return print "A cím mező kitöltése kötelező!";

if(!isset($_FILES["video"]))
    return print "Nem töltöttél fel semmit!";

if($_FILES["video"]["size"] > VIDEO_MAX_SIZE)
    return print "A videó nem lehet nagyobb, mint " . (VIDEO_MAX_SIZE / 1000000) . " mb!";


$videoExtension = strtolower(pathinfo($_FILES["video"]["name"], PATHINFO_EXTENSION));
if(!in_array($videoExtension, ACCEPTABLE_TYPES)) {
    $errorMessage = "Nem megfelelő a videó kiterjesztése!\n" .
                    "Elfogadott formátumok: " . join(", ", ACCEPTABLE_TYPES);
    return print $errorMessage;
}

$targetFilename = Video::generateUniqueId();
$targetLocation = VIDEO_TARGET_DIR . $targetFilename . "." . $videoExtension;
if(!move_uploaded_file($_FILES["video"]["tmp_name"], $targetLocation))
    return print "Váratlan hiba történt a videó feltöltése során!";



$cmd = "python3 videodata.py -f \"" . $targetLocation . "\" ";
$cmdOutput = "";

$thumbnailObj = null;
$videoDuration = 0;

// creating a thumbnail for video
exec($cmd . "-create_thumbnail", $cmdOutput);
echo $cmd;
$cmdOutput = trim($cmdOutput[0]);

if(file_exists($cmdOutput))
    $thumbnailObj = file_get_contents($cmdOutput);
else
    $thumbnailObj = file_get_contents("thumbnail.png"); // TODO: Change thumbnail path
unlink($cmdOutput);

// getting video duration
exec($cmd . "-get_duration", $cmdOutput);
echo "DURATION: " . var_dump($cmdOutput);
$cmdOutput = trim($cmdOutput[0]);


//$userObj = unserialize($_SESSION["userData"]);

$newVideo = new Video();
$newVideo->setVTitle($_POST["v_title"]);
//$newVideo->setVUploaderId($userObj->getUId());
$newVideo->setVUploaderId(1);
$newVideo->setVDuration($videoDuration);
$newVideo->setVDescription(empty($_POST["v_description"]) ? null : $_POST["v_description"]);
//$newVideo->setVVisibility($_POST["v_visibility"]);
$newVideo->setVVisibility(0);
$newVideo->setVThumbnail($thumbnailObj);
$newVideo->setVFilename($targetFilename);

$result = Video::addNew($newVideo);

if(!$result) {
    unlink($targetLocation);
    return print "Hiba történt a videó feltöltése közben!\nPróbáld újra később!";
} else {
    return print "A videód hamarosan közzétételre kerül!";
}

?>
