<?php

    define("_started", 1);
    require_once "include/general.php";
    startRoutine("action");

    if(!isset($_GET["action"]) || !DB::isLogged()) errorPage(404);

    $parameter = $_GET["action"];

    function jsonError($code, $message){
        $error = (object)array();
        $error->code = $code;
        $error->message = $message;
        echo json_encode($error);
        die();
    }

    function generalError(){
        errorPage(400, "Bad Request - Invalid parameter");
    }

    function createPost(){
        $obj = (object)array();
        $alphaNumericRegex = '/[a-zA-Z0-9]/';
        $dateNow = (new DateTime())->getTimestamp();
        $userid = DB::getUserID();
        $pin = null;
        $lock = null;

        if($_SERVER["REQUEST_METHOD"] != "POST") generalError();

        if(!isset($_POST["title"]) || !preg_match($alphaNumericRegex, $_POST["title"]) || mb_strlen($_POST["title"]) > 90) jsonError(0, "Title is faulty.");
        else if(!isset($_POST["textarea"]) || !preg_match($alphaNumericRegex, $_POST["textarea"]) || mb_strlen($_POST["textarea"]) > DB::$postLength) jsonError(0, "Text is faulty.");
        
        if(isset($_POST["lock"]) && $_POST["lock"] == "1") $lock = 1;
        if(isset($_POST["pin"]) && $_POST["pin"] == "1" && DB::isUpperPrivilege()) $pin = 1;

        $stat = DB::$conn->prepare("INSERT INTO ". DB::$postsdb ." (postTitle, contentText, datePosted, userID, pinned, locked) VALUES (:title, :text, :date, :user, :pin, :lock)");
        $stat->bindParam(":title", $_POST["title"]);
        $stat->bindParam(":text", $_POST["textarea"]);
        $stat->bindParam(":date", $dateNow);
        $stat->bindParam(":user", $userid);
        $stat->bindParam(":pin", $pin);
        $stat->bindParam(":lock", $lock);
        $stat->execute();

        if($stat->rowCount() == 0) jsonError(0, "Couldn't post.");
        else{
            $stat = DB::$conn->query("SELECT LAST_INSERT_ID()");
            $stat->execute();
            $result = $stat->fetch();
            $obj->code = 1;
            $obj->url = "/post.php?id=". $result[0];
            echo json_encode($obj);
        }
    }

    function createComment(){
        $obj = (object)array();
        $alphaNumericRegex = '/[a-zA-Z0-9]/';
        $dateNow = (new DateTime())->getTimestamp();
        $userid = DB::getUserID();

        if($_SERVER["REQUEST_METHOD"] != "POST") generalError();

        if(!isset($_POST["textarea"]) || !preg_match($alphaNumericRegex, $_POST["textarea"]) || mb_strlen($_POST["textarea"]) > DB::$commLength) jsonError(0, "Text is faulty.");
        else if(!isset($_POST["postid"]) || !preg_match($alphaNumericRegex, $_POST["postid"])) jsonError(0, "Specify the post id.");
        else{
            $stat = DB::$conn->prepare("SELECT * FROM ". DB::$postsdb ." WHERE postID = :post");
            $stat->bindParam(":post", $_POST["postid"]);
            $stat->execute();

            if($stat->rowCount() == 0) jsonError(0, "Post not found");
            else{
                if($stat->fetch()["locked"] == 1) jsonError(0, "Post has locked comments.");
                else{
                    $stat = DB::$conn->prepare("INSERT INTO ". DB::$commsdb ."(userID, postID, comText, datePosted) VALUES (:user, :post, :text, :date)");
                    $stat->bindParam(":user", $userid);
                    $stat->bindParam(":post", $_POST["postid"]);
                    $stat->bindParam(":text", $_POST["textarea"]);
                    $stat->bindParam(":date", $dateNow);
                    $stat->execute();

                    if($stat->rowCount() == 0) jsonError(0, "Couldn't post.");
                    else{
                        $obj->code = 1;
                        echo json_encode($obj);
                    }
                }
            }
        }
    }

    function deleteContent($name){
        $obj = (object)array();
        $userid = DB::getUserID();
        $table; $idname;
        
        if($name == "post"){
            $table = DB::$postsdb;
            $idname = "postID";
        }
        else if($name == "comment"){
            $table = DB::$commsdb;
            $idname = "comID";
        }

        if(!isset($_GET["id"])) generalError();

        $stat = DB::$conn->prepare("SELECT * FROM " . $table . " WHERE " . $idname . " = :id");
        $stat->bindParam(":id", $_GET["id"]);
        $stat->execute();

        if($stat->rowCount() == 0) jsonError(0, "Content not found");
        else{
            $result = $stat->fetch();

            if($result["userID"] == DB::getUserID() || DB::isUpperPrivilege()){
                try{
                    DB::$conn->beginTransaction();
                
                    if($name == "post"){
                        $stat = DB::$conn->prepare("DELETE FROM " . DB::$commsdb . " WHERE postID = :id");
                        $stat->bindParam(":id", $_GET["id"]);
                        $stat->execute();
                    }

                    $stat = DB::$conn->prepare("DELETE FROM " . $table . " WHERE " . $idname . " = :id");
                    $stat->bindParam(":id", $_GET["id"]);
                    $stat->execute();

                    DB::$conn->commit();
                }
                catch(PDOException $e){
                    DB::DBException($e);
                }

                if($stat->rowCount() == 0) jsonError(0, "Couldn't delete.");
                else{
                    $obj->code = 1;
                    if($name == "post") $obj->url = "/";
                    echo json_encode($obj);
                }
            }
            else jsonError(0, "You can't delete this content.");
        }
    }

    function createInvitation(){
        $id = DB::getUserID();
        $obj = (object)array();
        $stat = DB::$conn->prepare("SELECT * FROM ". DB::$settingsdb ." WHERE userID = :user");
        $stat->bindParam(":user", $id);
        $stat->execute();
        $result = $stat->fetch();
        if($result["invitation"] == null){
            $invitation;
            while(true){
                $invitation = strtoupper(substr(md5(mt_rand()), 0, 10));
                $stat = DB::$conn->query("SELECT invitation FROM ". DB::$settingsdb ." WHERE invitation = '". $invitation ."'");
                $stat->execute();
                if($stat->rowCount() == 0) break;
            }

            $stat = DB::$conn->prepare("UPDATE ". DB::$settingsdb ." SET invitation = :inv WHERE userID = :user");
            $stat->bindParam(":user", $id);
            $stat->bindParam(":inv", $invitation);
            $stat->execute();

            if($stat->rowCount() == 0) jsonError(0, "Error creating invitation.");

            $obj->code = 1;
            $obj->invitation = $invitation;
            echo json_encode($obj);
        }
        else jsonError(0, "You already have an active invitation.");
    }

    function changeSettings(){
        $id = DB::getUserID();
        $available = array("darkMode", "privateProfile", "invitation");
        $setting;
        $option;
        $value;

        if(!isset($_GET["setting"]) || !isset($_GET["option"]) || ($_GET["option"] != "1" && $_GET["option"] != "0")) generalError();
        else{
            $setting = $_GET["setting"];
            $option = $_GET["option"];

            if(array_search($setting, $available) === false) generalError();

            if($setting == "invitation" && $option == "1"){
                createInvitation();
                return;
            }
        }

        $value = $option;
        if($value == "0") $value = "NULL";

        $stat = DB::$conn->prepare("UPDATE ". DB::$settingsdb ." SET ". $setting ." = ". $value ." WHERE userID = :user");
        $stat->bindParam(":user", $id);
        $stat->execute();

        if($stat->rowCount() == 0) jsonError(0, "Couldn't change setting.");

        $obj = (object)array();
        $obj->code = 1;
        echo json_encode($obj);
    }

    function changePassword(){
        if(!isset($_POST["oldpass"]) || !isset($_POST["newpass"]) || !isset($_POST["newpass1"])) jsonError(0, "Fill every row");
        
        $id = DB::getUserID();
        $oldpass = $_POST["oldpass"];
        $newpass = $_POST["newpass"];
        $newpass1 = $_POST["newpass1"];

        if($newpass != $newpass1) jsonError(0, "New passwords are not the same");
        else if(strlen($newpass) < 6 || strlen($newpass) > 20) jsonError(0, "Password must be between 6 and 20 characters.");

        $encrypted = encryptPassword($newpass);
        $oldEncrypted = encryptPassword($oldpass);

        $stat = DB::$conn->prepare("SELECT * FROM ". DB::$usersdb ." WHERE userID = :user");
        $stat->bindParam(":user", $id);
        $stat->execute();
        $result = $stat->fetch();

        if($result["passCode"] != $oldEncrypted) jsonError(0, "The old password is wrong.");

        $stat = DB::$conn->prepare("UPDATE ". DB::$usersdb ." SET passCode = :pass WHERE userID = :user");
        $stat->bindParam(":user", $id);
        $stat->bindParam(":pass", $encrypted);
        $stat->execute();
        
        if($stat->rowCount() == 0) jsonError(0, "Couldn't change the password");
        $obj = (object)array();
        $obj->code = 1;
        echo json_encode($obj);
        DB::logoutUser(false);
    }

    function changeUser(){
        if(!DB::checkPrivilege(0) || !isset($_POST["user"]) || !isset($_POST["type"])) generalError();
        $obj = (object)array();

        $type = $_POST["type"];
        $user = $_POST["user"];
        $column;
        $value;

        if($type == "ban"){
            $column = "banned";
            $value = 1;
        }
        else if($type == "unban"){
            $column = "banned";
            $value = null;
        }
        else if($type == "promote"){
            $column = "privCode";
            $value = "1";
        }
        else if($type == "demote"){
            $column = "privCode";
            $value = "2";
        }
        else generalError();

        $stat = DB::$conn->prepare("SELECT * FROM ". DB::$usersdb ." WHERE privCode <> 0 AND userID = :user");
        $stat->bindParam(":user", $user);
        $stat->execute();
        
        if($stat->rowCount() == 0) jsonError(0, "You can't ". $type ." this user");

        $stat = DB::$conn->prepare("UPDATE ". DB::$usersdb . " SET ". $column ." = :value WHERE userID = :user");
        $stat->bindParam(":user", $user);
        $stat->bindParam(":value", $value);
        $stat->execute();

        if($stat->rowCount() == 0) jsonError(0, "Couldn't ". $type);

        $obj->code = 1;
        echo json_encode($obj);
    }

    if($parameter == "logout") DB::logoutUser();
    else if($parameter == "createPost") createPost();
    else if($parameter == "createComment") createComment();
    else if($parameter == "deletePost") deleteContent("post");
    else if($parameter == "deleteCom") deleteContent("comment");
    else if($parameter == "changeUser") changeUser();
    else if($parameter == "changeSettings") changeSettings();
    else if($parameter == "changePassword") changePassword();
    else generalError();
?>