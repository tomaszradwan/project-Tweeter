<?php

switch ($_SERVER['HTTP_REFERER']) {

    case "http://localhost/POZ_PHP_W_01_Warsztaty_OOP_SQL/project:Tweeter/showUserAccount.php":
        echo "<h1>YOU LOGGED OUT !</h1>

        <form action =  'index_.html' method = 'POST'>
        <input type = 'submit' name = 'logout' value = 'main page'/>
        </form >";


        break;

    case "http://localhost/POZ_PHP_W_01_Warsztaty_OOP_SQL/project:Tweeter/index_.html":
        echo "<h1>YOU CREATED A USER PROFILE.</h1>

        <form action =  'showUserAccount.php' method = 'POST'>
        <input type = 'submit' value = 'your profile'/>
        </form >";
        break;

    default:
        die("<h1>404 ERROR!<h1/>");
        break;
}




