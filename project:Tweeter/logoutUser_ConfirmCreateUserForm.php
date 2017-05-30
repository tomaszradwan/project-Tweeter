<?php

$re = '/\/.[a-zA-Z0-9]*\.[a-zA-Z]*/m';

$str = $_SERVER['HTTP_REFERER'];

preg_match($re, $str, $matches);

if (array_key_exists(0, $matches)) {

    $link = $matches[0];

    switch ($link) {

        case "/showUserAccount.php":
            echo "<h1>YOU LOGGED OUT !</h1>

        <form action =  'indexx.html' method = 'POST'>
        <input type = 'submit' name = 'logout' value = 'main page'/>
        </form >";
            break;

        case "/indexx.html":
            echo "<h1>YOU CREATED A USER PROFILE.</h1>

        <form action =  'showUserAccount.php' method = 'POST'>
        <input type = 'submit' value = 'your profile'/>
        </form >";
            break;

        default:
            die("<h1>404 ERROR!<h1/>");
            break;
    }
} else {
    die("<h1>ERROR - contact with the programmer!<h1/>");
}