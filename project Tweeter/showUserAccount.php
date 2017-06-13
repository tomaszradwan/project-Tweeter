<!DOCTYPE html>

<?php
session_start();

include 'User.php';
include 'Tweet.php';
include 'Comment.php';

$currentDate = date("Y-m-d");

if (isset($_SESSION['userId'])) {

    $userId = $_SESSION['userId'];
    $userName = User::loadUserById($userId)->getUserName();
} else {
    die("<h3>User don't exist!<br/></h3>");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $newTweet = new Tweet();

    if (isset($_POST['logout'])) {

        $_POST = array();

        unset($_SESSION['userId']);

        header('Location: logoutCreateDeleteUserForm.php');
    } elseif (isset($_POST['editUser'])) {

        header('Location: editUser.php');
    } elseif (isset($_POST['textTweet']) && isset($_POST['tweetDate'])) {

        $text = $_POST['textTweet'];
        $creationDate = $_POST['tweetDate'];

        $newTweet->setText($text);
        $newTweet->setCreationDate($creationDate);
        $newTweet->setUserId($userId);

        if (!$newTweet->saveToDB()) {
            die("Error - you cannot create a tweet!<br/>");
        }
    } elseif (isset($_POST['deleteTweet'])) {

        $tweetId = $_POST['deleteTweet'];
        Tweet::delete($tweetId);
    } elseif (isset($_POST['tweetIdComment']) && isset($_POST['commentTweet']) && isset($_POST['commentDate'])) {

        $tweet_Id = $_POST['tweetIdComment'];
        $text = $_POST['commentTweet'];
        $creationDate = $_POST['commentDate'];

        $newComment = new Comment ();
        $newComment->setTweetId($tweet_Id);
        $newComment->setUserId($userId);
        $newComment->setText($text);
        $newComment->setCreationDate($creationDate);

        if (!$newComment->saveToDB()) { {
                die("Error - cannot save your comment to tweet!<br/>");
            }
        }
    } elseif (isset($_POST['commentDelete'])) {
        $idComment = $_POST['commentDelete'];
        Comment::delete($idComment);
    }
}

/*
 * I use require at the end of the code, because in another way(at the beegining of the code) it doesn't work
 * (Undefined variables).
 * I don't use "file_get_contents" because in file "showUserAccountForm.php" I connected HTML with PHP to show
 * result(user comment, tweets, data). "File_get_contents" display code as a text without methods or functions.
 * 
 * Question for you Krzysztof:
 * this solution - require at the end of the code - it is good practice or not?
 */
require 'showUserAccountForm.php';
?>


