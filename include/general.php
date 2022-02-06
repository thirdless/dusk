<?php

    defined("_started") or errorPage(404);

    function startRoutine($page){
        session_start();
        date_default_timezone_set("Europe/Bucharest");

        if($page != "login" && $page != "register") DB::checkUser();
    }

    function errorPage($errNo, $details = ""){

        $errorText = "";
        $errorMessage = "";

        switch($errNo){
            case 404:
                $errorText = $errNo . " - Not Found";
                $errorMessage = "The page you're looking for might have been removed.";
            break;
            case 403:
                $errorText = $errNo . " - Forbidden";
                $errorMessage = "The resource you try to access is restricted.";
            break;
            case 500:
            case 503:
                $errorText = $errNo . " - Internal Server Error";
                $errorMessage = "The server couldn't handle this request.";
            break;
            case 401:
                $errorText = $errNo . " - Not Unauthorized";
                $errorMessage = "You need to be authenticated to do this operation.";
            break;
            case 405:
                $errorText = $errNo . " - Method Not Allowed";
                $errorMessage = "The method \"" . $details . "\" is not accepted on this server.";
            break;
            default:
                $errorText = "HTTP Error " . $errNo;
        }

        if($errNo != 405 && $details != "") $errorMessage = $details;

        $errorPageCode = $errNo;
        $errorPageText = $errorText;
        $errorPageMessage = $errorMessage;
        require_once "error.php";
        die();

    }

    function redirectPage($addr, $httpCode = 302){
        http_response_code($httpCode);
        header("Location: " . $addr);
        die("Click <a href='" . $addr . "'>here</a> to redirect.");
    }

    function parseSQList($string, $noTrim = 0){
        $exploded = explode(",", $string);
        for($i = 0; $i < count($exploded); $i++){
            $exploded[$i] = preg_replace("/\s.*/", "", trim($exploded[$i]));
        }
        return implode(',', $exploded);
    }

    function encryptPassword($pass){
        //cea mai buna practica ar fi bcrypt, dar din moment ce acesta este
        //un proiect micut, nu avem nevoie de cele mai bune standarde in
        //securitate, si vom folosi SHA256, care desi nu are salt si returneaza
        //rapid rezultatul este cel mai greu de crack-uit si este usor de implementat.
        // S-ar fi putut folosi si SHA1 dar a fost recent declarat criptografic 
        //nesigur, iar pentru MD5 pe internet exista cele mai mari dictionare de hash-uri sparte.
        return hash("sha256", $pass);
    }

    function getPostDate($value){
        return strtolower(date("j F Y \a\\t G:i", $value));
    }

    function padding($num){
        $text = "\n";
        for($i = 0; $i < $num; $i++) $text .= "\t";
        return $text;
    }

    class DB{
        public static $conn = null;
        private static $dbname = "genericforum",
                       $string2 = "root",
                       $string3 = "123456",
                       $host = "127.0.0.1",
                       $port = 3306;

        public static $usersdb = "Users",
                      $postsdb = "Posts",
                      $commsdb = "Comments",
                      $settingsdb = "Settings";

        public static $postLength = 3000;
        public static $commLength = 1000;

        private static $usersTemplate = "userID INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
                                         userName VARCHAR(25) NOT NULL UNIQUE,
                                         passCode TEXT NOT NULL,
                                         privCode INT NOT NULL DEFAULT 2,
                                         banned BOOLEAN,
                                         CHECK (privCode >= 0 AND privCode <= 2)";

        private static $postsTemplate = "postID INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
                                         postTitle TEXT NOT NULL,
                                         contentText TEXT NOT NULL,
                                         datePosted BIGINT NOT NULL,
                                         userID INT NOT NULL,
                                         pinned BOOLEAN,
                                         locked BOOLEAN,
                                         FOREIGN KEY(userID) REFERENCES Users(userID)";

        private static $commsTemplate = "comID INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
                                         userID INT NOT NULL,
                                         postID INT NOT NULL,
                                         comText TEXT NOT NULL,
                                         datePosted BIGINT NOT NULL,
                                         FOREIGN KEY(userID) REFERENCES Users(userID),
                                         FOREIGN KEY(postID) REFERENCES Posts(postID)";

        private static $settingsTemplate = "userID INT PRIMARY KEY NOT NULL,
                                            invitation VARCHAR(10) UNIQUE,
                                            darkMode BOOLEAN,
                                            privateProfile BOOLEAN,
                                            FOREIGN KEY(userID) REFERENCES Users(userID)";

        public static function connect(){
            if(self::$conn == null){
                try{
                    self::$conn = new PDO("mysql:host=".self::$host, self::$string2, self::$string3);
                    self::$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    self::checkEmpty();
                }
                catch(PDOException $e){
                    errorPage(500, "Database connection error");
                }
            }
        }

        public static function checkUser(){
            if(!self::isLogged()) redirectPage("/login.php");
            else{
                $id = self::getUserID();
                self::connect();
                $stat = self::$conn->prepare("SELECT userID, banned, privCode FROM " . self::$usersdb . " WHERE userID = :user");
                $stat->bindParam(":user", $id);
                $stat->execute();

                if($stat->rowCount() == 0) self::logoutUser();

                $result = $stat->fetch();
                
                $_SESSION["privilege"] = $result["privCode"];

                if($result["banned"] == 1){
                    self::logoutUser();
                }
            }
        }

        public static function createUser($username, $password, $invitation){
            $warning = "";
            $encryptedPass = encryptPassword($password);

            try{
                if(strlen($username) < 5 || strlen($username) > 20) $warning = "Username must be between 5 and 20 characters.";
                else if(preg_match("/^[a-zA-Z0-9]+$/", $username) != 1) $warning = "Username must contain only letters and numbers.";
                else if(strlen($password) < 6 || strlen($password) > 20) $warning = "Password must be between 6 and 20 characters.";
                else if(strlen($invitation) < 5) $warning = "Invalid invitation.";
                else{
                    self::connect();
                    self::$conn->beginTransaction();
        
                    $stat = self::$conn->prepare("SELECT * FROM " . self::$settingsdb . " WHERE invitation = :inv");
                    $stat->bindParam(":inv", $invitation);
                    $stat->execute();
                    $result = $stat->fetch();
                    if($stat->rowCount() == 0) $warning = "Invalid invitation.";
                    else{
                        $stat = self::$conn->prepare("SELECT * FROM " . self::$usersdb . " WHERE userName = :user");
                        $stat->bindParam(":user", $username);
                        $stat->execute();
                        if($stat->rowCount() != 0) $warning = "Username taken. Try another one.";
                        else{
                            //can create user
                            $stat = self::$conn->prepare("INSERT INTO " . self::$usersdb . " (userName, passCode) VALUES (:name, :pass);
                            UPDATE " . self::$settingsdb . " SET invitation = NULL WHERE invitation = :inv");
                            $stat->bindParam(":name", $username);
                            $stat->bindParam(":pass", $encryptedPass);
                            $stat->bindParam(":inv", $invitation);
                            $stat->execute();
                            if($stat->rowCount() == 0) throw new Exception("Error creating user.");
                            else{
                                $stat = self::$conn->prepare("SELECT * FROM " . self::$usersdb . " WHERE userName = :user");
                                $stat->bindParam(":user", $username);
                                $stat->execute();
                                $result = $stat->fetch();
                                self::setSessionParams($result);

                                self::$conn->exec("INSERT INTO ". self::$settingsdb ." (userID) VALUES (". $result["userID"] .")");

                                self::$conn->commit();

                                redirectPage("/"); //ready to use the site
                            }
                        }
                    }
                }
            }
            catch(PDOException $e){
                self::DBException($e);
            }

            return $warning;
        }

        private static function checkEmpty(){
            require_once "test.php";

            if(self::$conn->query("show databases like '" . self::$dbname . "'")->rowCount() == 0){
                self::execDB("create database ". self::$dbname);
            }

            self::$conn->exec("use " . self::$dbname);

            createTable(self::$usersdb, self::$usersTemplate);
            createTable(self::$postsdb, self::$postsTemplate);
            createTable(self::$commsdb, self::$commsTemplate);
            createTable(self::$settingsdb, self::$settingsTemplate);

            if(self::$conn->query("select * from ". self::$usersdb)->rowCount() == 0){
                createUserTest("admin", "adminpass", 0, 1);
                createUserTest("moderator", "modpass", 1, 1);
                createUserTest("user1", "userpass1");
                createUserTest("user2", "userpass2");
                createUserTest("user3", "userpass3");
            }

            if(self::$conn->query("select * from ". self::$postsdb)->rowCount() == 0){
                createPostTest("Forum Rules", "templates/rules.template", 1, 1, 1);
                createPostTest("iPhone has a text notifications bug", "templates/iphone.template", 2);
                createPostTest("New Music From Taylor Swift, Kid Cudi, Jack Harlow & More", "templates/music.template", 3);
                createPostTest("[AskJS] Best practices on securing 3rd party client side api requests?", "templates/question.template", 4);
                createPostTest("My dad kept all glasses he's ever worn since 1964", "templates/glasses.template", 5);
            }

            if(self::$conn->query("select * from ". self::$commsdb)->rowCount() == 0){
                //https://www.reddit.com/r/javascript/comments/kawo5i/askjs_best_practices_on_securing_3rd_party_client/
                $commText = <<<EOT
                There needs to be some connection between your server and their server. One option could go like this:

                - 3rd party site calls your server's API from their secure backend to obtain a user token.   
                - 3rd party site sends this user token to their client JavaScript.
                - Whenever the widget is initialized on the 3rd party site, the user token would be sent to your server.
                - Because your server initially issued the token, you know which user owns that token. Assuming you trust the 3rd party server, then this is a secure way to identify users.
EOT;
                
                createCommTest("You know what else is bugged to death… BUG SUR", 2, 3);
                createCommTest("Jack leveling up again!", 3, 5);
                createCommTest($commText, 4, 2);
                createCommTest("@moderator Thanks, makes sense!", 4, 4);
                createCommTest("HAHA XD", 5, 1);
            }
        }

        public static function setSessionParams($results){
            $_SESSION["logged"] = 1;
            $_SESSION["userID"] = $results["userID"];
            $_SESSION["userName"] = $results["userName"];
            $_SESSION["privilege"] = $results["privCode"];
        }

        public static function logoutUser($redirect = true){
            unset($_SESSION["logged"]);
            if($redirect == true) redirectPage("/");
        }

        public static function isUpperPrivilege(){
            return self::checkPrivilege(0) || self::checkPrivilege(1);
        }

        public static function checkPrivilege($code){
            return $_SESSION["privilege"] == $code;
        }

        public static function getUserID(){
            return $_SESSION["userID"];
        }

        public static function isLogged(){
            return isset($_SESSION["logged"]);
        }

        public static function darkModeClass(){
            $id = self::getUserID();
            $stat = self::$conn->prepare("SELECT darkMode FROM ". self::$settingsdb ." WHERE userID = :user");
            $stat->bindParam(":user", $id);
            $stat->execute();

            $result = $stat->fetch();
            if($result["darkMode"] == 1) echo 'class="dark"';
        }

        public static function execDB($query){
            try{
                self::$conn->beginTransaction();
                $res = self::$conn->exec($query);
                self::$conn->commit();
                return $res;
            }
            catch(PDOException $e){
                self::DBException($e);
            }
        }

        public static function DBException($e){
            if(self::$conn->inTransaction()) self::$conn->rollBack();
            errorPage(500, $e->getMessage());
        }

    }

?>