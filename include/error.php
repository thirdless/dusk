<?php

    if(!isset($errorPageCode)) $errorPageCode = 404;
    if(!isset($errorPageText)) $errorPageText = "404 - Not Found";
    if(!isset($errorPageMessage)) $errorPageMessage = "The resource you try to access is restricted.";

    http_response_code($errorPageCode);

?>
<!DOCTYPE html>
<html>
    <head>
        <title>Dusk - BD Proiect</title>
        <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&family=Pacifico&display=swap" rel="stylesheet">
        <meta charset="utf-8"/>
        <link rel="stylesheet" href="/assets/index.css"/>
    </head>
    <body>
        <div class="main error">
            <div class="content">
                <div class="flash">Oops!</div>
                <div class="sub"><?php echo $errorPageText; ?></div> 
                <div class="text"><?php echo $errorPageMessage; ?></div> 
                <a class="gen_button" href="/">Back to homepage</a>
            </div>
        </div>
    </body>
</html>