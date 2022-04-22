<html>
    <head>

    </head>
    <body>
        <form method="post" action="newmention.php" enctype="multipart/form-data">
            Video: <select name="v_id">
                <?php

                require_once("db/models/Video.php");

                foreach(Video::findAll() as $video) {
                    echo "<option value='" . $video->getVId() . "'>" . $video->getVTitle() . "</option>";
                }

                ?>
            </select><br>
            Parent mention: <select name="m_parent">
                <?php

                require_once("db/models/Mention.php");

                echo "<option value='0'>No parent mention</option>";
                foreach(Mention::findAll() as $mention) {
                    echo "<option value='" . $mention->getMId() . "'>" . substr($mention->getMText(), 0, 50) . "</option>";
                }

                ?>
            </select><br>
            <textarea name="m_text"></textarea>
            <input type="submit" name="submit" value="Upload">
        </form>
    </body>
</html>