<?php

session_start();

include 'User.php';

$userId = $_SESSION['userId'];

$userName = User::getById($userId)->getUserName();
$userEmail = User::getById($userId)->getUserEmail();

$textConfirmChanges = "";
$textWrongPassword = "";

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
        }
        $textConfirmChanges = "Changes made in DataBase!";
    } elseif (isset($_POST['userAccount'])) {

        header('Location: showUserAccount.php');
    } elseif (isset($_POST['deleteUser'])) {

        $userPassword = $_POST['userPassword'];

        if (User::delete($userId, $userPassword)) {

            unset($_SESSION['userId']);

            header('Location: logoutCreateDeleteUserForm.php');
        } else {
            $textWrongPassword = "Podałeś błędne hasło!<br/>";
        }
    }
}

$search = array('%userName%', '%userEmail%', '%textConfirmChanges%', '%textWrongPassword%');
$replace = array($userName, $userEmail, $textConfirmChanges, $textWrongPassword);

$html = file_get_contents("editUserForm.html");
$page = str_replace($search, $replace, $html);

echo $page;
?>

