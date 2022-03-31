<?php

require_once("DBConnector.php");

$conn = new DBConnector();

echo "Vardump: " . var_dump($conn->getConn()) . "<br>";

$conn->closeConn();

?>