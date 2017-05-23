<?php

include "User.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['emaillogin']) && isset($_POST['passwordlogin'])) {

        $email = $_POST['emaillogin'];
        $pass = $_POST['passwordlogin'];

        $passVerify = User::pass_verify_by_email($email, $pass);

        if ($passVerify) {
            echo "Zalogowany!<br/>";
        } else {
            die("Błędne hasło!");
        }
    }
} else {
    die("Brak użytkownika");
}