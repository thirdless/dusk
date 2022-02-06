<?php

    define("_started", 1);
    require_once "include/general.php";
    startRoutine("home");

    $recentPosts = "";
    $noPosts = 0;
    $searchEnabled = false;
    $searchBar = "";

    function fetchPosts($result){
        global $noPosts;
        global $recentPosts;
        if(count($result) != 0){
            foreach($result as $key => $value){
                $noPosts++;
                $recentPosts .= '<a class="post" href="/post.php?id=' . $value["postID"] . '">'. padding(6) .'<div class="title">' . htmlspecialchars($value["postTitle"]);
                if($value["pinned"] == 1) $recentPosts .= '<svg class="pin"><use xlink:href="#pin"></use></svg>';
                if($value["locked"] == 1) $recentPosts .= '<svg class="lock"><use xlink:href="#lock"></use></svg>';
                $recentPosts .= '</div>'. padding(6) .'<div class="details">Posted by <svg><use xlink:href="#user"></use></svg> ' . htmlspecialchars($value["userName"]) . ' <span>on ' . getPostDate($value["datePosted"]) . '</span></div>'. padding(5) .'</a>' . padding(5);
            }
        }
    }

    try{
        if(isset($_GET["search"])){
            $searchEnabled = true;
            $searchString = "%" . $_GET["search"] . "%";
            $searchBar = htmlspecialchars($_GET["search"]);

            $stat = DB::$conn->prepare("SELECT * FROM " . DB::$postsdb . " p join " . DB::$usersdb . " u WHERE postTitle LIKE :text AND p.userID = u.userID ORDER BY datePosted DESC");
            $stat->bindParam(":text", $searchString);
            $stat->execute();
            $result = $stat->fetchAll(PDO::FETCH_ASSOC);
            fetchPosts($result);
        }
        else{
            $stat = DB::$conn->prepare("SELECT * FROM " . DB::$postsdb . " p join " . DB::$usersdb . " u WHERE pinned = 1 AND p.userID = u.userID ORDER BY datePosted DESC");
            $stat->execute();
            $result = $stat->fetchAll(PDO::FETCH_ASSOC);
            fetchPosts($result);
        
            $stat = DB::$conn->prepare("SELECT * FROM " . DB::$postsdb . " p join " . DB::$usersdb . " u WHERE (pinned IS NULL OR pinned = 0) AND p.userID = u.userID ORDER BY datePosted DESC");
            $stat->execute();
            $result = $stat->fetchAll(PDO::FETCH_ASSOC);
            fetchPosts($result);
        }
    }
    catch(PDOException $e){
        errorPage(500, $e->getMessage());
    }

    if($noPosts == 0){
        if($searchEnabled) $recentPosts = "<p>No posts found for your search query.</p>";
        else $recentPosts = "<p>No recent posts. Be the first one to post something!</p>";
    }

?>
<!DOCTYPE html>
<html>
    <head>
        <title>Dusk - BD Proiect</title>
        <?php require "include/headTag.php"; ?>
    </head>
    <body <?php DB::darkModeClass() ?>>
        <div class="main home">
            <div class="background"></div>
            <?php require "include/header.php"; ?>
            <div class="content">
                <div class="search">
                    <svg><use xlink:href="#search"></use></svg>
                    <input type="text" class="bar" placeholder="Search a post..." value="<?php echo $searchBar ?>">
                </div><?php if(!$searchEnabled){?> 
                <div class="create">
                    <input type="text" class="title" maxlength="90" placeholder="Choose a title...">
                    <textarea placeholder="Start typing a post..." oninput="auto_grow(this)" maxlength="<?php echo DB::$postLength ?>"></textarea>
                    <div class="details">Post as <svg><use xlink:href="#user"></use></svg><?php echo htmlspecialchars($_SESSION["userName"]); ?></div>
                    <div class="option lockpost"><svg><use xlink:href="#lock"></use></svg><div class="switch"></div></div>
                    <?php if(DB::isUpperPrivilege()){ ?><div class="option pinpost"><svg><use xlink:href="#pin"></use></svg><div class="switch"></div></div><?php } ?> 
                    <div class="gen_button submit">Submit</div>
                    <div class="help open-modal" data-open="help-bbcodes"><svg><use xlink:href="#help"></use></svg> Help</div>
                </div><?php }?> 
                <div class="recent">
                    <?php echo $recentPosts; ?> 
                </div>
            </div>
        </div>
        <script src="/assets/index.js"></script>
    </body>
</html>