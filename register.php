<?php

    define("_started", 1);
    require_once "include/general.php";
    startRoutine("register");

    if(DB::isLogged()) redirectPage("/");

    if($_SERVER["REQUEST_METHOD"] != "GET" && $_SERVER["REQUEST_METHOD"] != "POST"){
        errorPage(405, htmlspecialchars($_SERVER["REQUEST_METHOD"]));
    }

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $username = $_POST["username"];
        $password = $_POST["password"];
        $password1 = $_POST["password1"];
        $invitation = $_POST["invitation"];

        $warning = "";

        if($password != $password1) $warning = "Passwords must be the same.";
        else $warning = DB::createUser($username, $password, $invitation);
    }

    if(isset($warning) && $warning !== ""){
        $warning = "<div class=\"warning\">$warning</div>";
    }

?>
<!DOCTYPE html>
<html>
    <head>
        <title>Register - Dusk - BD Proiect</title>
        <?php require_once "include/headTag.php"; ?>
    </head>
    <body>
        <div class="main login-page register">
            <div class="logo">Dusk</div>
            <div class="content">
                <div class="text">Register now</div>
                <?php if(isset($warning)) echo $warning; ?> 
                <div class="separator">
                    <form name="form" action="/register.php" method="POST">
                        <input type="text" name="username" placeholder="Username" autocomplete="off" minlength="5" maxlength="20" pattern="^[a-zA-Z0-9]+$"/>
                        <div class="sep"></div>
                        <input type="password" minlength="6" maxlength="20" name="password" placeholder="Password"/>
                        <div class="sep"></div>
                        <input type="password" minlength="6" maxlength="20" name="password1" placeholder="Confirm Password"/>
                        <div class="sep"></div>
                        <input type="text" name="invitation" placeholder="Invitation Code" autocomplete="off" pattern="^[a-zA-Z0-9]+$"/>
                        <div class="sep"></div>
                        <a href="/login.php">Login</a>
                        <input class="gen_button" type="submit" value="Register">
                    </form>
                </div>
            </div>
        </div>
        <script src="/assets/index.js"></script>
    </body>
</html>