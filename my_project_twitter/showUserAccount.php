<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->


<?php
session_start();

include 'User.php';
include 'Tweet.php';

if (isset($_SESSION['userId'])) {

    $userId = $_SESSION['userId'];
    $userName = User::loadUserById($userId)->getUserName();
    $userid = User::loadUserById($userId)->getId();

//    unset($_SESSION['userId']);
} else {
    die("User don't exist!<br/>");
}
?>
<html>
    <head>
        <title>User Account & Tweets</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css"
    </head>

    <body>

        <form action="goodByeForm.php" method="POST">
            <input type="submit" name="logout" value="wyloguj"/>
        </form>

        <hr width="500px" align="left">

        <h2> Witaj, <?php echo $userName; ?> !</h2>
        <h6> Twój numer ID: <?php echo $userId; ?> !</h6>

        <hr width="500px" align="left">

        <?php
        $userTweets = Tweet::loadAllTweetsByUserId($userId);
        ?>

        <h4>Twoje tweety:</h4>
        <!--<table border="1">-->
        <table class="w3-table w3-striped w3-bordered">
            <tr>
                <th>ID</th>
                <th>userId</th>
                <th>text</th>
                <th>creationDate</th>
            </tr>

            <?php foreach ($userTweets as $key => $value): ?>
                <tr>
                    <td><?php echo $value->getId() ?></td>
                    <td><?php echo $value->getUserId() ?></td>
                    <td><?php echo $value->getText() ?></td>
                    <td><?php echo $value->getCreationDate() ?></td>

                </tr>
                <?php
            endforeach;
            ?>

        </table>
    </body>
</html>
