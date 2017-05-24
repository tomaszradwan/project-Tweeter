<?php

session_start();

include "User.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['emaillogin']) && isset($_POST['passwordlogin'])) {

        $email = $_POST['emaillogin'];
        $pass = $_POST['passwordlogin'];

        $passVerify = User::pass_verify_by_email($email, $pass);

        if ($passVerify) {
            $userId = User::loadUserByEmail($email)->getId();
            $_SESSION['userId'] = $userId;
            header('Location: showUserAccount.php');

            die("Zalogowany!<br/>");
        } else {
            die("Błędne hasło!<br/>");
        }
    }
} else {
    die("Brak użytkownika w bazie<br/>");
}