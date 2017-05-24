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

    unset($_SESSION['userId']);
} else {
    die("User don't exist!<br/>");
}
?>
<html>
    <head>
        <title>User Account & Tweets</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>

    <body>

        <form action="goodByeForm.php" method="POST">
            <input type="submit" name="logout" value="wyloguj"/>
        </form>

        <h2> Witaj <?php echo $userName; ?> !</h2>

        <?php
        $userTweets = Tweet::loadAllTweetsByUserId($userId);
        ?>

        <h4>Twoje tweety:</h4>
        <table border="1">
            <tr>
                <th>ID</th>
                <th>userId</th>
                <th>text</th>
                <th>creationDate</th>
            </tr>

            <?php
            foreach ($userTweets as $key => $value) {

                echo "<tr><td>" . $value->getId() . "</td>";
                echo "<td>" . $value->getUserId() . "</td>";
                echo "<td>" . $value->getText() . "</td>";
                echo "<td>" . $value->getCreationDate() . "</td></tr>";
            }
            ?>

        </table>
    </body>
</html>
