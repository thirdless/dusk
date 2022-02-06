<?php

    require_once "general.php";

    defined("_started") or errorPage(404);

    function createUserTest($userName, $password, $privCode = 2, $private = 0){
        DB::execDB("insert into ". DB::$usersdb ."(userName, passCode, privCode) values ('". $userName ."', '". encryptPassword($password) ."', ". $privCode .")");
        DB::execDB("insert into ". DB::$settingsdb ." (userID, privateProfile) values ((select userID from ". DB::$usersdb ." where userName = '". $userName ."'), ". $private .")");
    }

    function createPostTest($title, $url, $userID, $locked = 0, $pinned = 0){
        $date = (new DateTime())->getTimestamp();
        $text = @file_get_contents($url);
        
        try{
            DB::$conn->beginTransaction();
            $stat = DB::$conn->prepare("insert into ". DB::$postsdb . "(postTitle, contentText, datePosted, userID, pinned, locked) values(:title, :text, :date, :userid, :pinned, :locked)");
            $stat->bindParam(":title", $title);
            $stat->bindParam(":text", $text);
            $stat->bindParam(":date", $date);
            $stat->bindParam(":userid", $userID);
            $stat->bindParam(":pinned", $pinned);
            $stat->bindParam(":locked", $locked);
            $stat->execute();
            DB::$conn->commit();
        }
        catch(PDOException $e){
            DB::DBException($e);
        }
    }

    function createCommTest($text, $postID, $userID){
        $date = (new DateTime())->getTimestamp();

        try{
            DB::$conn->beginTransaction();
            $stat = DB::$conn->prepare("insert into ". DB::$commsdb ." (userID, postID, comText, datePosted) values (:user, :post, :text, :date)");
            $stat->bindParam(":user", $userID);
            $stat->bindParam(":post", $postID);
            $stat->bindParam(":text", $text);
            $stat->bindParam(":date", $date);
            $stat->execute();
            DB::$conn->commit();
        }
        catch(PDOException $e){
            DB::DBException($e);
        }
    }

    function createTable($name, $template){
        if(DB::$conn->query("show tables like '" . $name . "'")->rowCount() == 0){
            DB::execDB("create table " . $name . " (" . $template . ")");
        }
    }

?>