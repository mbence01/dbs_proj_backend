<?php

require_once("DBConnector.php");

$db = new DBConnector();
$conn = $db->getConn();

$tableNames = array(  "ACCOUNT",
                            "CATEGORY",
                            "MENTION",
                            "PLAYLIST",
                            "TAG",
                            "TAGTOVIDEO",
                            "USERFAVOURITEVIDEO",
                            "USERFOLLOWINGUSER",
                            "USERLOGIN",
                            "USERREACTEDVIDEO",
                            "USERVIEWEDVIDEO",
                            "VIDEO",
                            "VIDEOINCATEGORY",
                            "VIDEOINPLAYLIST" );

?>

<html>
    <head>
        <title>YT Lite - CRUD</title>
        
        <style>

        </style>
    </head>
    <body>
        <div id="table-selector">
            <?php

            if(!isset($_GET["table"]) or !in_array($_GET["table"], $tableNames))
                echo "<h1 class='error'>Válassz egy táblát!</h1>";

            foreach($tableNames as $table) {
                echo "<a href='crud.php?table=" . $table . "'>" . $table . "</a>";
            }

            ?>
        </div><hr>
        <div id="new-record-container">
            <?php

            if(!isset($_GET["table"]) or !in_array($_GET["table"], $tableNames))
                echo "<h1 class='error'>Válassz egy táblát!</h1>";
            else {
                $stid = oci_parse($conn, "SELECT * FROM " . $_GET["table"]);

                oci_execute($stid, OCI_DESCRIBE_ONLY);

                echo "<p>Beszúrás a(z) " . $_GET["table"] . " táblába...</p>";
                echo "<form action='crud.php?type=insert&table=" . $_GET["table"] . "' method='post'>";

                for($i = 2; $i <= oci_num_fields($stid); $i++) {
                    $type = "";
                    $name = oci_field_name($stid, $i);

                    switch(oci_field_type($stid, $i)) {
                        case "NUMBER": {
                            $type = "number";
                            break;
                        }

                        case "VARCHAR2": {
                            $type = "text";
                            break;
                        }

                        case "DATE": {
                            $type = "date";
                            break;
                        }

                        case "TIMESTAMP": {
                            $type = "datetime";
                            break;
                        }

                        default: {
                            $type = "text";
                        }
                    }

                    echo $name . ": <input type='" . $type . "' name='" . $name . "'><br>";
                }
                echo "<input type='submit' value='Hozzáad'></form>";
            }

            ?>
        </div><hr>
        <div id="list-container">
            <?php

            if(!isset($_GET["table"]) or !in_array($_GET["table"], $tableNames))
                echo "<h1 class='error'>Válassz egy táblát!</h1>";
            else {
                $stid = oci_parse($conn, "SELECT * FROM " . $_GET["table"]);

                oci_execute($stid);

                $c = 0;

                echo "<p>A(z) " . $_GET["table"] . " tábla tartalma</p>";
                echo "<table>";
                while($row = oci_fetch_assoc($stid)) {
                    echo "<form action='crud.php?type=edit&table=" . $_GET["table"] . "' method='post'>";
                    echo "<tr>";

                    foreach($row as $key => $val) {
                        $type = "";

                        switch(oci_field_type($stid, ++$c)) {
                            case "NUMBER": {
                                $type = "number";
                                break;
                            }

                            case "VARCHAR2": {
                                $type = "text";
                                break;
                            }

                            case "DATE": {
                                $type = "date";
                                break;
                            }

                            case "TIMESTAMP": {
                                $type = "datetime";
                                break;
                            }

                            default: {
                                $type = "text";
                            }
                        }

                        echo "<td><input type='" . $type . "' name='" . $key . "' value='" . $val . "'></td>";
                    }
                    $c = 0;

                    echo "<td><input type='submit'></td>";
                    echo "</tr></form>";
                }
                echo "</table>";
            }

            ?>
        </div>
    </body>
</html>
