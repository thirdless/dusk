<?php

    define("_started", 1);
    require_once "include/general.php";
    startRoutine("user");

    $profileID = DB::getUserID();
    $profileUsername = $_SESSION["userName"];
    $showProfile = false;
    $profileText = "";
    $activeUsers = "";

    function getPrivilege($number){
        if($number == 0) return "admin";
        else if($number == 1) return "moderator";
        else return "user";
    }

    function fetchLatest($table, $textColumn, $postColumn, $max){
        global $profileID;
        $count = 0;
        $ret = "";

        $stat = DB::$conn->prepare("SELECT * FROM ". $table ." WHERE userID = :user ORDER BY datePosted DESC");
        $stat->bindParam(":user", $profileID);
        $stat->execute();
        $result = $stat->fetchAll();

        foreach($result as $row){
            $count++;
            if($count > $max) break;

            $text = str_replace("\n", " ", htmlspecialchars($row[$textColumn]));
            $ret .= "<a class=\"post\" href=\"/post.php?id=". htmlspecialchars($row[$postColumn]) ."\"><div>". $text ."</div><span>on ". getPostDate($row["datePosted"]);
            $ret .=  "</span></a>". padding(5);
        }

        return $ret;
    }

    function showProfile($noRecent){
        global $profileText;

        $lastPosts = fetchLatest(DB::$postsdb, "postTitle", "postID", $noRecent);
        $lastComments = fetchLatest(DB::$commsdb, "comText", "postID", $noRecent);

        $profileText = <<<EOT

            <div class="content">
                <div class="option">
                    <div class="subtitle">Most recent {$noRecent} posts</div>
                    {$lastPosts}
                </div>
                <div class="option">
                    <div class="subtitle">Most recent {$noRecent} comments</div>
                    {$lastComments}
                </div>
            </div>
EOT;
    }

    if(isset($_GET["id"])){
        //show profile
        $showProfile = true;

        $stat = DB::$conn->prepare("SELECT * FROM ". DB::$settingsdb ." s JOIN ". DB::$usersdb ." u WHERE s.userID = :user AND s.userID = u.userID");
        $stat->bindParam(":user", $_GET["id"]);
        $stat->execute();

        if($stat->rowCount() == 0) errorPage(404);

        $result = $stat->fetch();
        $profileUsername = $result["userName"];
        $profileID = $result["userID"];
        
        if($result["privateProfile"] == 1 && (DB::getUserID() != $result["userID"] && !DB::isUpperPrivilege())){
            $profileText = '<div class="privateProfile"><svg><use xlink:href="#lock"></use></svg> This profile is private</div>';
        }
        else{
            showProfile(5);
        }
    }
    else{
        //show settings
        $id = DB::getUserID();
        $stat = DB::$conn->prepare("SELECT * FROM ". DB::$settingsdb ." WHERE userID = :user");
        $stat->bindParam(":user", $id);
        $stat->execute();

        $result = $stat->fetch();
        if($result["invitation"] != null) $invitation = $result["invitation"];
        if($result["darkMode"] != null) $darkMode = $result["darkMode"];
        if($result["privateProfile"] != null) $privProf = $result["privateProfile"];

        if($_SESSION["privilege"] == 0 || $_SESSION["privilege"] == 1){
            $activeUsers .= '<div class="row">'. padding(5) .'<div class="subtitle">Active users</div>'. padding(5) .'<div class="list">';

            $stat = DB::$conn->query("SELECT * FROM ". DB::$usersdb);
            $stat->execute();
            $result = $stat->fetchAll();

            foreach($result as $row){
                $activeUsers .= padding(6) .'<div class="user" data-user="'. $row["userID"] .'">'. padding(7) .'<a href="/user.php?id='. $row["userID"] .'">'. $row["userID"] .". ";
                $activeUsers .= htmlspecialchars($row["userName"]) ." - " . getPrivilege($row["privCode"]) . ($row["banned"] ? " - <span style='color:red'>Banned</span>" : "") . '</a>';
                if($row["privCode"] != 0 && $_SESSION["privilege"] == 0){
                    $activeUsers .= padding(7);
                    if($row["banned"] == 1) $activeUsers .= '<div title="Unban" class="ban" data-action="unban"><svg><use xlink:href="#recover"></use></svg></div>';
                    else $activeUsers .= '<div title="Ban" class="ban" data-action="ban"><svg><use xlink:href="#ban"></use></svg></div>';

                    $activeUsers .= padding(7);
                    if($row["privCode"] == 2) $activeUsers .= '<div class="promote" title="Promote" data-action="promote"><svg><use xlink:href="#promote"></use></svg></div>';
                    else $activeUsers .= '<div class="promote" title="Demote" data-action="demote"><svg><use xlink:href="#user"></use></svg></div>';
                }
                $activeUsers .= padding(6) ."</div>";
            }

            $activeUsers .= padding(5) ."</div>". padding(4) ."</div>";
        }
    }

?>
<!DOCTYPE html>
<html>
    <head>
        <title>User - Dusk - BD Proiect</title>
        <?php require "include/headTag.php"; ?>
    </head>
    <body <?php DB::darkModeClass() ?>>
        <div class="main user">
            <div class="background"></div>
            <?php require "include/header.php"; ?>
            <div class="details">
                <svg><use xlink:href="#user"></use></svg><?php echo htmlspecialchars($profileUsername) ?> 
            </div>
            <?php echo $profileText; ?>
            <?php if(!$showProfile){ ?> 
            <div class="content">
                <div class="row">
                    <div class="subtitle">User settings</div>
                    <div class="setting dark">
                        <div class="big">Dark mode</div>
                        <div class="switch <?php if(isset($darkMode)) echo "on" ?>"></div>
                    </div>
                    <div class="setting private">
                        <div class="big">Private profile</div>
                        <div class="switch <?php if(isset($privProf)) echo "on" ?>"></div>
                    </div>
                    <div class="setting invitation">
                        <?php if(isset($invitation)){ ?> 
                        <div class="big">Your invitation: <?php echo $invitation ?></div>
                        <div class="gen_button submit" data-action="delete">Delete</div>
                        <?php }else{ ?> 
                        <div class="big">Create Invitation</div>
                        <div class="gen_button submit" data-action="create">Create</div>
                        <?php } ?> 
                    </div>
                    <div class="setting password">
                        <div class="big">Change Password</div>
                        <div class="gen_button open-modal pass" data-open="change-pass">Change Password</div>
                    </div>
                </div>
                <?php echo $activeUsers ?> 
            </div>
            <?php } ?> 
        </div>
        <script src="/assets/index.js"></script>
    </body>
</html>