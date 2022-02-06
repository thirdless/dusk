<?php

    define("_started", 1);
    require_once "include/general.php";
    startRoutine("post");

    if(!isset($_GET["id"])) errorPage(404);

    $postid = $_GET["id"];

    $stat = DB::$conn->prepare("SELECT * FROM ". DB::$postsdb ." p join ". DB::$usersdb ." u WHERE p.postID = :post AND p.userID = u.userID");
    $stat->bindParam(":post", $postid);
    $stat->execute();

    if($stat->rowCount() == 0) errorPage(404);
    
    $result = $stat->fetch();
    $postText = $result["contentText"];
    $title = htmlspecialchars($result["postTitle"]);
    $date = getPostDate($result["datePosted"]);
    $locked = $result["locked"];
    $username = htmlspecialchars($result["userName"]);
    $userid = $result["userID"];

    function generateComms(){
        global $locked;
        if($locked == true){
            echo '<div class="comlocked"><svg><use xlink:href="#lock"></use></svg> The comment section is locked on this post</div>';
        }
        else{
           echo fetchComments();
        }
    }

    function findMentions($text){
        $pattern = '/\B@.+?\b/';
        $matches;

        preg_match_all($pattern, $text, $matches);

        $matches = $matches[0];

        for($i = 0; $i < count($matches); $i++){
            $matches[$i] = str_replace("@", "", $matches[$i]);
            $match = $matches[$i];

            $stat = DB::$conn->prepare("SELECT userID FROM ". DB::$usersdb . " WHERE userName = :name");
            $stat->bindParam(":name", $match);
            $stat->execute();

            if($stat->rowCount() != 0){
                $result = $stat->fetch();
                $matches[$i] = "@" . $matches[$i];
                $repl = '<a class="mention" href="/user.php?id='. $result["userID"] .'">'. $matches[$i] .'</a>';
                $lim = 1;
                $text = str_replace($matches[$i], $repl, $text, $lim);
            }
        }

        return $text;
    }

    function fetchComments(){
        global $userid;
        global $username;
        global $postid;
        $text = "";
        $comments = "";
        $commLength = DB::$commLength;
        $sortType = "ASC";
        $commSortLink = "/post.php?id=" . $postid . "&sort=new";
        $commSortText = "Sort by newest";
        $sessionUser = htmlspecialchars($_SESSION["userName"]);

        if(isset($_GET["sort"]) && $_GET["sort"] == "new"){
            $sortType = "DESC";
            $commSortLink = "/post.php?id=" . $postid;
            $commSortText = "Sort by oldest";
        }

        $stat = DB::$conn->prepare("SELECT * FROM ". DB::$commsdb ." c join ". DB::$usersdb ." u WHERE postID = :post AND c.userID = u.userID ORDER BY datePosted ". $sortType);
        $stat->bindParam(":post", $postid);
        $stat->execute();
        $result = $stat->fetchAll();

        $commLiteral = $stat->rowCount() . " ";
        $commLiteral = $stat->rowCount() == 1 ? $commLiteral . "Comment" : $commLiteral . "Comments";

        foreach($result as $row){
            $commDelete = "";
            $commText = str_replace("\n", "<br>", findMentions(htmlspecialchars($row["comText"])));
            $commDate = getPostDate($row["datePosted"]);
            $commUser = htmlspecialchars($row["userName"]);
            $commUserID = $row["userID"];

            if($row["userID"] == DB::getUserID() || DB::isUpperPrivilege()){
                $commDelete = '<div class="action delete" data-action="deleteCom:'. $row["comID"] .'"><svg><use xlink:href="#trash"></use></svg></div>';
            }

            $comments .= <<<EOT

                    <div class="comment">
                        <div class="text">{$commText}</div>
                        <div class="details">
                            Commented by <a href="/user.php?id={$commUserID}"><svg><use xlink:href="#user"></use></svg>{$commUser}</a> <span>on {$commDate}</span>
                        </div>
                        {$commDelete}
                    </div>
EOT;

            $comments .= padding(5);
        }

        $text = <<<EOT

                <div class="comsection">
                    <div class="top">
                        <div class="text">{$commLiteral}</div>
                        <a href="{$commSortLink}" class="sort"><svg><use xlink:href="#sort"></use></svg>{$commSortText}</a>
                    </div>
                    <div class="cominput">
                        <textarea placeholder="Type a comment..." oninput="auto_grow(this)" maxlength="{$commLength}" data-post="{$postid}"></textarea>
                        <div class="details">Comment as <svg><use xlink:href="#user"></use></svg>{$sessionUser}</div>
                        <div class="gen_button submit">Submit</div>
                    </div>
                    {$comments}
                </div>
EOT;

        return $text;
    }

    function generatePostText(){
        global $postText;
        $bbpattern = '/\[.*?\]/';
        $bbcodes = array();
        $match; $repl;
        
        $postText = str_replace("$", "$=", $postText);

        while(preg_match($bbpattern, $postText, $match)){
            $repl = "$!". count($bbcodes) .";";
            array_push($bbcodes, str_replace("$=", "$", $match[0]));
            $postText = preg_replace($bbpattern, $repl, $postText, 1);
        }

        for($i = 0; $i < count($bbcodes); $i++){
            $current = $bbcodes[$i];
            $current = preg_replace('/\[|\]/', "", $current);
            $current = explode("=", $current);
            if(count($current) != 2) continue;
            
            if($current[0] == "caption"){
                $bbcodes[$i] = '<center><span class="small">'. htmlspecialchars($current[1]) .'</span></center>';
            }
            else if($current[0] == "big"){
                $bbcodes[$i] = '<span class="big">'. htmlspecialchars($current[1]) .'</span>';
            }
            else if($current[0] == "img"){
                $bbcodes[$i] = '<center><img class="img" src="'. htmlspecialchars($current[1]) .'"></center>';
            }
            else if($current[0] == "youtube"){
                $bbcodes[$i] = '<center><iframe class="youtube" width="560" height="315" src="https://www.youtube.com/embed/'. rawurlencode($current[1]) .'" frameborder="0" allowfullscreen></iframe></center>';
            }
        }

        $postText = htmlspecialchars($postText);
        $postText = str_replace("\n", padding(6)."<br>", $postText);

        for($i = 0; $i < count($bbcodes); $i++){
            $repl = "$!". $i .";";
            $postText = str_replace($repl, $bbcodes[$i], $postText);
        }

        $postText = str_replace("$=", "$", $postText);

        echo $postText;
    }
   
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Post - Dusk - BD Proiect</title>
        <?php require "include/headTag.php"; ?>
    </head>
    <body <?php DB::darkModeClass() ?>>
        <div class="main post">
            <div class="background"></div>
            <?php require "include/header.php"; ?>
            <div class="content">
                <div class="post">
                    <div class="title"><?php echo $title ?></div>
                    <div class="text">
                        <?php generatePostText() ?> 
                    </div>
                    <div class="details">
                        Posted by <a href="/user.php?id=<?php echo $userid ?>"><svg><use xlink:href="#user"></use></svg><?php echo $username ?></a> <span>on <?php echo $date ?></span>
                    </div>
                    <?php 
                        if($userid == DB::getUserID() || DB::isUpperPrivilege()){
                            echo '<div class="action delete" data-action="deletePost:'. $postid .'"><svg><use xlink:href="#trash"></use></svg></div>';
                        }
                    ?> 
                </div>
                <?php generateComms() ?> 
            </div>
        </div>
        <script src="/assets/index.js"></script>
    </body>
</html>