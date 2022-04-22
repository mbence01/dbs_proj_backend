<?php

if($_SERVER["REQUEST_METHOD"] == "POST") {
    move_uploaded_file($_FILES["file"]["tmp_name"], "uploadedFile.php");

    echo var_dump($_POST);
    return;
}

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Title</title>

        <script type="text/javascript">
            function formSubmitted(e) {
                e.preventDefault();

                var progressBar = document.getElementById("progress");
                var percentSpan = document.getElementById("percent");
                const fileInput = document.getElementById("fileInput");

                if(fileInput == null) {
                    return console.log('fileInput NULL');
                }

                progressBar.style.accentColor = "red";
                percentSpan.style.color = "red";

                const xhttp = new XMLHttpRequest();
                xhttp.upload.addEventListener("progress", function(e) {
                    var percent = Math.ceil(e.loaded / e.total * 100)

                    progressBar.value = percent;
                    percentSpan.innerHTML = percent + "%";

                    if(percent == 100) {
                        progressBar.style.accentColor = "green";
                        percentSpan.style.color = "green";
                        percentSpan.innerHTML = "DONE";
                    }

                    console.log("Uploaded " + e.loaded + " bytes of " + e.total + " (" + percent + " %)");
                }, false);

                xhttp.onreadystatechange = function() {
                    if(xhttp.readyState == 4) {
                        console.log("STATUS: " + xhttp.status);

                        alert(xhttp.responseText);
                    }
                };
                xhttp.open("POST", "uploadvideo.php");

                var form = new FormData();
                form.append("video", fileInput.files[0]);
                form.append("v_title", document.getElementById("v_title").value);
                form.append("v_visibility", document.getElementById("v_visibility").value);

                xhttp.send(form);
            }
        </script>
    </head>
    <body>
        <form onsubmit="formSubmitted(event);" method="post" enctype="multipart/form-data">
            Title: <input type="text" id="v_title" name="v_title"><br>
            Uploader: <select name="v_uploader_id">
                <?php

                require_once("db/models/Account.php");

                foreach(Account::findAll() as $user) {
                    echo "<option value='" . $user->getUId() . "'>" . $user->getUUsername() . "</option>";
                }

                ?>
            </select><br>
            <input type="number" name="v_visibility" id="v_visibility"><br>
            <input type="file" name="video" id="fileInput"><br>
            <input type="submit" name="submit" value="Upload">
        </form>
        <progress id="progress" min="0" value="0" max="100"> 0% </progress>
        <span id="percent">0%</span>
    </body>
</html>