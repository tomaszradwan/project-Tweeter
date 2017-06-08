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
        $this->hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);
    }

    public function setUserName($username) {
        $this->username = $username;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function setId($id) {
        $this->id = $id;
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

    static public function loadUserById($idNumber) {

        $connection = new Connection();

        $id = $connection->conn->real_escape_string(trim($idNumber));

        $sql = "SELECT * FROM Users WHERE id=$id";

        $result = $connection->conn->query($sql);

        if ($result == true && $result->num_rows == 1) {
            $row = $result->fetch_assoc();
            $user = new User();
            $user->setId($row['id']);
            $user->setUserName($row['username']);
            $user->setPassword($row['hashed_password']);
            $user->setEmail($row['email']);
            return $user;
        }
        return null;
    }

    static public function loadAllUsers() {

        $connection = new Connection();

        $sql = "SELECT * FROM Users";

        $allUsers = array();

        $result = $connection->conn->query($sql);

        if ($result == true && $result->num_rows != 0) {
            foreach ($result as $row) {
                $user = new User();
                $user->setId($row['id']);
                $user->setUserName($row['username']);
                $user->setPassword($row['hashed_password']);
                $user->setEmail($row['email']);

                $allUsers[] = $loadedUser;
            }
        }
        return $allUsers;
    }

    static public function delete($idNumber, $pass) {

        $connection = new Connection();

        $id = $connection->conn->real_escape_string(trim($idNumber));

        if ($id > 0 && passVerifyById($id, $pass) == true) {

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

    public function passVerifyById($idNumber, $pass) {

        $connection = new Connection();

        $id = $connection->conn->real_escape_string($idNumber);

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

    static public function passVerifyByEmail($email, $pass) {

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

        $verifyEmail = filter_var($email, FILTER_VALIDATE_EMAIL);

        $sql = "SELECT * FROM `Users` WHERE `email`='$verifyEmail'";

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

    static public function verifyEmailInDB($email) {

        $connection = new Connection();

        $verifyEmail = filter_var($email, FILTER_VALIDATE_EMAIL);

        $sql = "SELECT `email` FROM `Users` WHERE `email` = '$verifyEmail'";

        $result = $connection->querySql($sql);

        if ($result) {
            foreach ($result as $row) {
                $emailChecked = $row['email'];
            }
        }
        return $emailChecked;
    }

    static public function updateUser($user_Id, $user_Name, $email) {

        $connection = new Connection();

        $verifyEmail = filter_var($email, FILTER_VALIDATE_EMAIL);

        $userName = $connection->conn->real_escape_string(trim($user_Name));
        $userId = $connection->conn->real_escape_string(trim($user_Id));

        $sqlTableId = "SELECT `id` FROM `Users`";

        $resultTableId = $connection->querySql($sqlTableId);

        $tableWithId = array();

        if ($resultTableId->num_rows > 0) {

            foreach ($resultTableId as $key => $value) {
                $tableWithId[] = $value['id'];
            }

            if (in_array($userId, $tableWithId)) {

                $sql = "UPDATE `Users` SET `username`= '$userName',`email`='$email' WHERE `id` = '$userId'";

                $result = $connection->querySql($sql);

                if ($result) {
                    return true;
                } else {
                    return false;
                }
            }
        }
        return false;
    }

}
