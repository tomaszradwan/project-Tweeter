<?php

session_start();

include "User.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['emaillogin']) && isset($_POST['passwordlogin'])) {

        $email = $_POST['emaillogin'];
        $pass = $_POST['passwordlogin'];

        $emailVerify = User::verifyEmailInDB($email);

        if (!$email == $emailVerify) {
            die("Email not exist in database!<br/>");
        }

        $passVerify = User::passVerifyByEmail($email, $pass);

        if ($passVerify) {
            $userId = User::loadUserByEmail($email)->getId();
            $_SESSION['userId'] = $userId;
            header('Location: showUserAccount.php');
        } else {
            die("Wrong password!<br/>");
        }
    } elseif (isset($_POST['nameregister']) && isset($_POST['emailregister']) && isset($_POST['passregister'])) {

        $name = $_POST['nameregister'];
        $email = $_POST['emailregister'];
        $password = $_POST['passregister'];

        $newUser = new User();

        $emailVerify = User::verifyEmailInDB($email);

        if ($email == $emailVerify) {
            die("Email already exist in database!<br/>");
        }

        $newUser->setUserName($name);
        $newUser->setEmail($email);
        $newUser->setPassword($password);

        if ($newUser->saveToDB()) {

            $userId = $newUser->getId();

            $_SESSION['userId'] = $userId;
            header('Location: logoutUser_ConfirmCreateUserForm.php');
        } else {
            die('An error occurred while writing data to the database!<br/>');
        }
    }
} else {
    die("<h2>Database error, please contact with the programmer!<h2/><br/>");
}