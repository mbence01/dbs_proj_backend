<html>
    <head>
    </head>
    <body>
    <?php

    if(isset($_GET["page"]) and $_GET["page"] == "login") { ?>
        <form action="login.php" method="post">
            <input type="text" name="u_username">
            <input type="password" name="u_password">
            <input type="submit" value="Login">
        </form><?php
    } else { ?>
        <form action="reg.php" method="post" enctype="multipart/form-data">
            Username: <input type="text" name="u_username"><br>
            Password1: <input type="password" name="u_password"><br>
            Password2: <input type="password" name="u_password2"><br>
            E-mail: <input type="email" name="u_email"><br>
            Born date: <input type="date" name="u_born_date"><br>
            <label for="cb">Public:</label> <input type="checkbox" id="cb" name="u_public"><br>
            Profile: <input type="file" name="u_profile"><br>
            <input type="submit" value="Login">
        </form><?php
    }

    ?>

    </body>
</html>