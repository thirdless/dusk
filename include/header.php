<?php
    require_once "general.php";
?>
<div class="header">
                <a href="/"><div class="logo">Dusk</div></a>
                <div class="menu">
                    <svg><use xlink:href="#user"></use></svg> <?php echo htmlspecialchars($_SESSION["userName"]) ?> 
                    <div class="dropdown">
                        <a href="/user.php?id=<?php echo DB::getUserID() ?>">Profile</a>
                        <a href="/user.php">Settings</a>
                        <a class="logout" href="/action.php?action=logout">Logout</a>
                    </div>
                </div>
            </div>
