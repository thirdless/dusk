<?php

    define("_started", 1);
    require_once "include/general.php";
    startRoutine("login");

    if(DB::isLogged()) redirectPage("/");

    if($_SERVER["REQUEST_METHOD"] != "GET" && $_SERVER["REQUEST_METHOD"] != "POST"){
        errorPage(405, htmlspecialchars($_SERVER["REQUEST_METHOD"]));
    }

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $username = $_POST["username"];
        $password = $_POST["password"];
        $encryptedPass = encryptPassword($password);

        DB::connect();

        $stat = DB::$conn->prepare("SELECT * FROM " . DB::$usersdb . " WHERE userName = :user AND passCode = :pass");
        $stat->bindParam(":user", $username);
        $stat->bindParam(":pass", $encryptedPass);
        $stat->execute();

        if($stat->rowCount() == 0){
            $warning = "Wrong username/password";
        }
        else{
            $result = $stat->fetch();
           
            if($result["banned"] == 1) $warning = "Your account has been banned. Please contact an admin for more informations.";
            else{
                DB::setSessionParams($result);
                redirectPage("/");
            }
        }

        if(isset($warning)){
            $warning = "<div class=\"warning\">$warning</div>";
        }
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Login - Dusk - BD Proiect</title>
        <?php require_once "include/headTag.php"; ?> 
    </head>
    <body>
        <div class="main login-page login">
            <div class="logo">Dusk</div>
            <div class="content">
                <div class="text">Login now to start</div>
                <?php if(isset($warning)) echo $warning; ?> 
                <div class="separator">
                    <form name="form" action="/login.php" method="POST">
                        <input type="text" name="username" placeholder="Username" autocomplete="off"/>
                        <div class="sep"></div>
                        <input type="password" name="password" placeholder="Password"/>
                        <div class="sep"></div>
                        <a href="/register.php">Create Account</a>
                        <input class="gen_button" type="submit" value="Submit">
                    </form>
                </div>
            </div>
        </div>
        <script src="/assets/index.js"></script>
    </body>
</html>