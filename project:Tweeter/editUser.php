<?php

session_start();

include 'User.php';

$userId = $_SESSION['userId'];

$userName = User::loadUserById($_GET['userId'])->getUserName();
$userEmail = User::loadUserById($_GET['userId'])->getUserEmail();

$text = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['userName']) || isset($_POST['userEmail'])) {

        $userName = $_POST['userName'];
        $email = $_POST['userEmail'];

        try {
            if (!User::updateUser($userId, $userName, $email)) {
                throw new Exception("ERROR - you cannot update your data!");
            }
        } catch (Exception $ex) {
            die($ex->getMessage());
        } $text = "Changes made in DataBase!";
    } elseif (isset($_POST['userAccount'])) {
        header('Location: showUserAccount.php');
    }
}



$search = array('%userName%', '%userEmail%', '%text%');
$replace = array("$userName", "$userEmail", "");

$page = file_get_contents("editUserForm.html");
$page = str_replace($search, $replace, $page);

echo $page;
?>

