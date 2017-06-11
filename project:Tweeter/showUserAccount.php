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
 * I don't use "file_get_contents" because in file "showUserAccountForm.php" I mix html with php to print
 * result(user comment, tweets, data). "File_get_contents" display code as a text without methods or function.
 * 
 * Question for you Krzysztof:
 * this solution - require at the end of the code - it is good practice or not(probably it's not - I assume)?
 */
require 'showUserAccountForm.php';
?>
<!--<html>
    <head>
        <title>User Account & Tweets</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    </head>

    <body>

        <form action="#" method="POST" style="padding: 11px">
            <input type="submit" name="logout" value="logout"/>
        </form>

        <form action="#" method="POST" style="padding: 11px">
            <input type="submit" name="editUser" value="edit User"/>
        </form>

        <hr width="500px" align="left">

        <h1> Welcome, <?php echo $userName; ?> !</h1>
        <h6> Your ID: <?php echo $userId; ?> !</h6>

        <hr width="500px" align="left">


        <h3>Your tweets with comments another user's:</h3>

        <table class="w3-table w3-striped w3-bordered">
            <tr>
                <th>ID</th>
                <th>UserId</th>
                <th>Text</th>
                <th>Creation Date</th>
                <th>Delete Tweet</th>
            </tr>

<?php
$userTweets = Tweet::getByUserId($userId);

if ($userTweets != null) {
    foreach ($userTweets as $key => $value):
        ?>
                                                                                            <tr>
                                                                                                <td><?php echo $value->getId() ?></td>
                                                                                                <td><?php echo $value->getUserId() ?></td>
                                                                                                <td><?php echo $value->getText() ?></td>
                                                                                                <td><?php echo $value->getCreationDate() ?></td>
                                                                                                <td><form action="#" method="POST"><button type="submit" name="deleteTweet" value="<?php echo $value->getId() ?>">delete tweet</button></form></td>
        <?php
        $commentByTweetId = Comment::getTweetById($value->getId());

        if (count($commentByTweetId) > 0) {
            foreach ($commentByTweetId as $key => $value):
                ?>
                                                                                                                                                                            <tr>
                                                                                                                                                                                <td></td>
                                                                                                                                                                                <td><?php echo $value->getUserId() ?></td>
                                                                                                                                                                                <td><?php echo $value->getText() ?></td>
                                                                                                                                                                                <td><?php echo $value->getCreationDate() ?></td>
                                                                                                                                                                            </tr>
                <?php
            endforeach;
        }
    endforeach;
}
?>
        </table>

        <h3>Create a new tweet:</h3>
        <table class="w3-table w3-striped w3-bordered">
            <tr>
                <th>Text</th>
                <th>Creation Date</th>
                <th>Create Tweet</th>
            </tr>
            <tr>
            <form action="#" method="POST">
                <td><textarea name="textTweet" placeholder="wpisz wiadomość" rows="8" cols="20" maxlength="140" height="20" size="20" required></textarea></td>
                <td><input name="tweetDate" type="text" value="<?php echo $currentDate ?>" readonly/></td>
                <td><input name="tweetDate" type="date"/></td>
                <td><input type="submit" name="newtweet" value="create"/></td>
            </form>
        </tr>
    </table>

    <h3>All comments you created :</h3>
    <table class="w3-table w3-striped w3-bordered">
        <tr>
            <th>Id</th>
            <th>UserID</th>
            <th>TweetId</th>
            <th>Comment</th>
            <th>Creation Date of Comment</th>
            <th>Delete Comment</th>
        </tr>
<?php
$allCommentsByUser = Comment::getByUserId($userId);

if ($allCommentsByUser != null) {
    foreach ($allCommentsByUser as $key => $value):
        ?>
                                                                                        <tr>
                                                                                        <form method="POST" action="#">
                                                                                            <td><?php echo $value->getId() ?></td>
                                                                                            <td><?php echo $value->getUserId() ?></td>
                                                                                            <td><?php echo $value->getTweetId() ?></td>
                                                                                            <td><?php echo $value->getText() ?></td>
                                                                                            <td><?php echo $value->getCreationDate() ?></td>
                                                                                            <td><form action="#" method="POST"><button type="submit" name="commentDelete" value="<?php echo $value->getId() ?>">delete comment</button></form></td>
                                                                                        </form>
                                                                                    </tr>
        <?php
    endforeach;
}
?>
</table>

<h3>All tweet's in DB (you can comment):</h3>
<table class="w3-table w3-striped w3-bordered">
    <tr>
        <th>TweetId</th>
        <th>UserID</th>
        <th>Text</th>
        <th>Creation Date of Tweet</th>
        <th>Your Comment</th>
        <th>Creation Date of comment</th>
        <th>Add your comment</th>
    </tr>

<?php
$allTweets = Tweet::loadAllTweets();
if ($allTweets != null) {
    foreach ($allTweets as $key => $value):
        ?>
                                                                                    <tr>
                                                                                    <form method="POST" action="#">
                                                                                        <td><input type="hidden" name="tweetIdComment" value="<?php echo $value->getId() ?>"><?php echo $value->getId() ?></td>
                                                                                        <td><input type="hidden" name="userIdComment" value="<?php echo $value->getUserId() ?>"><?php echo $value->getUserId() ?></td>
                                                                                        <td><input type="hidden" name="text" value="<?php echo $value->getText() ?>"><?php echo $value->getText() ?></td>
                                                                                        <td><input type="hidden" name="creationDate" value="<?php echo $value->getCreationDate() ?>"><?php echo $value->getCreationDate() ?></td>
                                                                                        <td><textarea name="commentTweet" placeholder="your message"></textarea></td>
                                                                                        <td><input name="commentDate" type="text" value="<?php echo $currentDate ?>" readonly/></td>
                                                                                        <td><input type="submit" name="commentButton" value="comment"></td>
                                                                                    </form>
                                                                                </tr>
        <?php
    endforeach;
}
?>
</table>
</body>
</html>-->
