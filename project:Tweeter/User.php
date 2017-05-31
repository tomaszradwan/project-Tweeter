<?php

include_once 'Connection.php';

class User {

    private $id;
    private $username;
    private $hashedPassword;
    private $email;

    public function __construct() {

        $this->id = -1;
        $this->username = "";
        $this->email = "";
        $this->hashedPassword = "";
    }

    public function setPassword($newPassword) {
        $newHashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);
        $this->hashedPassword = $newHashedPassword;
    }

    public function setUserName($username) {
        $this->username = $username;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function getId() {
        return $this->id;
    }

    public function getUserName() {
        return $this->username;
    }

    public function getUserEmail() {
        return $this->email;
    }

    public function saveToDB() {

        $connection = new Connection();

        if ($this->id == -1) {

            $sql = "INSERT INTO `Users`(`username`, `email`, `hashed_password`) VALUES ('$this->username', '$this->email', '$this->hashedPassword')";
            $result = $connection->querySql($sql);

            if ($result == true) {

                $this->id = $connection->conn->insert_id;
                return true;
            }
        }
        return false;
    }

    static public function loadUserById($id) {

        $connection = new Connection();

        $id = $connection->conn->real_escape_string($id);

        $sql = "SELECT * FROM Users WHERE id=$id";

        $result = $connection->conn->query($sql);


        if ($result == true && $result->num_rows == 1) {
            $row = $result->fetch_assoc();
            $loadedUser = new User();
            $loadedUser->id = $row['id'];
            $loadedUser->username = $row['username'];
            $loadedUser->hashedPassword = $row['hashed_password'];
            $loadedUser->email = $row['email'];
            return $loadedUser;
        }
        return null;
    }

    static public function loadAllUsers() {

        $connection = new Connection();

        $sql = "SELECT * FROM Users";

        $ret = [];

        $result = $connection->conn->query($sql);

        if ($result == true && $result->num_rows != 0) {
            foreach ($result as $row) {
                $loadedUser = new User();
                $loadedUser->id = $row['id'];
                $loadedUser->username = $row['username'];
                $loadedUser->hashedPassword = $row['hashed_password'];
                $loadedUser->email = $row['email'];

                $ret[] = $loadedUser;
            }
        }
        return $ret;
    }

    static public function delete($id, $pass) {

        $connection = new Connection();

        if ($id > 0 && $this->pass_verify_by_id($id, $pass) == true) {

            $sql = $connection->conn->prepare("DELETE FROM Users WHERE id= ?");

            if ($sql == true) {
                $sql->bind_param('i', $id);
                $sql->execute();
            } else {
                echo $connection->conn->error;
            }
        } else {
            echo "Brak użytkownika o id $id";
            return false;
        }
    }

    protected function pass_verify_by_id($id, $pass) {

        $connection = new Connection();

        $id = $connection->conn->real_escape_string($id);

        $sql = "SELECT hashed_password FROM Users WHERE id=$id";
        $result = $connection->querySql($sql);

        $score = null;

        while ($row = $result->fetch_row()) {
            if ($score = $row[0]) {
                $score = $row[0];
            }
        }

        if ($score != null) {
            return password_verify($pass, $score);
        } else {
            return false;
        }
    }

    static public function pass_verify_by_email($email, $pass) {

        $connection = new Connection();

        $verifyEmail = filter_var($email, FILTER_VALIDATE_EMAIL);

        $sql = "SELECT `hashed_password` FROM `Users` WHERE `email`='$verifyEmail'";
        $result = $connection->querySql($sql);

        $score = null;

        while ($row = $result->fetch_row()) {
            if ($score = $row[0]) {
                $score = $row[0];
            }
        }

        if ($score != null) {
            return password_verify($pass, $score);
        } else {
            return false;
        }
    }

    static public function loadUserByEmail($email) {

        $connection = new Connection();

        $sql = "SELECT * FROM `Users` WHERE `email`='$email'";

        $result = $connection->conn->query($sql);

        if ($result == true && $result->num_rows == 1) {
            $row = $result->fetch_assoc();
            $loadedUser = new User();
            $loadedUser->id = $row['id'];
            $loadedUser->username = $row['username'];
            $loadedUser->email = $row['email'];
            return $loadedUser;
        }
        return null;
    }

    static public function loadAllEmails($email) {

        $connection = new Connection();

        $sql = "SELECT `email` FROM `Users` WHERE `email` = '$email'";

        $result = $connection->querySql($sql);

        $table = array();

        if ($result == true && $result->num_rows == 1) {
            foreach ($result as $row) {
                $table = $row['email'];
            }
        }
        return $table;
    }

    static public function updateUser($userId, $userName, $email) {

        $connection = new Connection();

        $sql = "UPDATE `Users` SET `username`= '$userName',`email`='$email' WHERE `id` = '$userId'";

        $result = $connection->querySql($sql);

        if ($result) {
            return true;
        } else {
            return false;
        }
    }

}
