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
    }
} else {
    die("Błąd bazy danych, zgłoś się do administratora systemu!<br/>");
}