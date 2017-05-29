<?php

session_start();

include "User.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['emaillogin']) && isset($_POST['passwordlogin'])) {

        $email = $_POST['emaillogin'];
        $pass = $_POST['passwordlogin'];

        $emailChecked = User::loadAllEmails($email);

        try {
            if (!$email == $emailChecked) {
                throw new Exception("Podany email nie znajduje się w bazie użytkowników!<br/>");
            } else {
                $email;
            }
        } catch (Exception $exc) {
            die($exc->getMessage());
        }

        $passVerify = User::pass_verify_by_email($email, $pass);

        if ($passVerify) {
            $userId = User::loadUserByEmail($email)->getId();
            $_SESSION['userId'] = $userId;
            header('Location: showUserAccount.php');
        } else {
            die("Błędne hasło!<br/>");
        }
    } elseif (isset($_POST['nameregister']) && isset($_POST['emailregister']) && isset($_POST['passregister'])) {

        $name = $_POST['nameregister'];
        $email = $_POST['emailregister'];
        $password = $_POST['passregister'];

        $newUser = new User();

        $emailChecked = User::loadAllEmails($email);

        try {
            if ($email == $emailChecked) {
                throw new Exception("Podany email znajduje się w bazie użytkowników!<br/>");
            } else {
                $email;
            }
        } catch (Exception $exc) {
            die($exc->getMessage());
        }

        $newUser->setUserName($name);
        $newUser->setEmail($email);
        $newUser->setPassword($password);

        if ($newUser->saveToDB()) {

            $userId = $newUser->getId();

            $_SESSION['userId'] = $userId;
            header('Location: logoutRegisterForm.php');
        } else {
            die('An error occurred while writing data to the database!<br/>');
        }
    }
} else {
    die("<h2>Data Base error, contact with the programmer!<h2/><br/>");
}