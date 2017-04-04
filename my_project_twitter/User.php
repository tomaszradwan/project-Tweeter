<?php

include 'Database.php';

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

    public function getID() {
        return $this->id;
    }

    public function saveToDB() {

        $connection = new Database;

        if ($this->id == -1) {

            $sql = "INSERT INTO users(username, email, hashed_password) VALUES ('$this->username', '$this->email', '$this->hashedPassword')";
            $result = $connection->querySql($sql);

            if ($result == true) {
                $this->id = $connection->connection->insert_id;
                return true;
            }
        }
        return false;
    }

    static public function loadUserById($id) {

        $connection = new Database;

        $sql = "SELECT * FROM users WHERE id=$id";

        $result = $connection->connection->query($sql);


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

        $connection = new Database;

        $sql = "SELECT * FROM users";

        $ret = [];

        $result = $connection->connection->query($sql);

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

    public function delete($id, $pass) {

        $connection = new Database;

        if ($id > 0 && $this->pass_verify($id, $pass) === true) {

            $sql = "DELETE FROM users WHERE id=$id";
            $result = $connection->connection->query($sql);

            if ($result) {
                return true;
            } else {
                return false;
            }
        }
    }

    protected function pass_verify($id, $pass) {

        $connection = new Database();

        $sql = "SELECT hashed_password FROM users WHERE id=$id";
        $result = $connection->querySql($sql);

        while ($row = $result->fetch_row()) {
            if ($score = $row[0]) {
                $score = $row[0];
            } else {
                return false;
            }
        }

        if (password_verify($pass, $score)) {
            return true;
        } else {
            return false;
        }
    }

}

$nowy = new User;
$nowy->setUserName('tomek');
$nowy->setPassword('pass123');
$nowy->setEmail('tomek@tomek.pl');
$nowy->saveToDB();

$test = new User;
$test->setUserName('radwan');
$test->setPassword('123pass');
$test->setEmail('radwan@radwan.pl');
$test->saveToDB();


//echo $nowy->pass_verify(171, 'tomeffk12');
//echo $nowy->delete(265, 'tomeffk12');
//var_dump(User::loadUserById(33));
//var_dump(User::loadAllUsers());

