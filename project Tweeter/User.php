<?php

include_once 'Connection.php';

class User {

    /**
     *
     * @var Integer 
     */
    private $id = -1;

    /**
     *
     * @var String 
     */
    private $username = "";

    /**
     *
     * @var String 
     */
    private $hashedPassword = "";

    /**
     *
     * @var String 
     */
    private $email = "";

    /**
     * 
     * @param type String
     */
    public function setPassword($newPassword) {
        $this->hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);
    }

    /**
     * 
     * @param type String
     */
    public function setUserName($username) {
        $this->username = $username;
    }

    /**
     * 
     * @param type String
     */
    public function setEmail($email) {
        $this->email = $email;
    }

    /**
     * 
     * @param type Integer
     */
    public function setId($id) {
        $this->id = $id;
    }

    /**
     * 
     * @return type
     */
    public function getId() {
        return $this->id;
    }

    /**
     * 
     * @return type
     */
    public function getUserName() {
        return $this->username;
    }

    /**
     * 
     * @return type
     */
    public function getUserEmail() {
        return $this->email;
    }

    /**
     * 
     * @return boolean
     */
    public function saveToDB() {

        $connection = new Connection();

        if ($this->id == -1) {

            $sql = "INSERT INTO `Users`(`username`, `email`, `hashed_password`) VALUES ('$this->username', '$this->email', '$this->hashedPassword')";
            $result = $connection->querySql($sql);

            if ($result) {
                $this->id = $connection->getConnection()->insert_id;
                return true;
            }
        }
        return false;
    }

    /**
     * 
     * @param type Integer
     * @return \User
     */
    static public function getById($id) {

        $connection = new Connection();

        $id = $connection->getConnection()->real_escape_string(trim($id));

        $sql = "SELECT * FROM Users WHERE id=$id";

        $result = $connection->getConnection()->query($sql);

        if ($result == true && $result->num_rows == 1) {
            $row = $result->fetch_assoc();
            $user = new User();
            $user->setId($row['id']);
            $user->setUserName($row['username']);
            $user->setPassword($row['hashed_password']);
            $user->setEmail($row['email']);
            return $user;
        }
        return;
    }

    /**
     * 
     * @return 
     */
    static public function getAll() {

        $connection = new Connection();

        $sql = "SELECT * FROM Users";

        $allUsers = array();

        $result = $connection->getConnection()->query($sql);

        if ($result == true && $result->num_rows > 0) {
            foreach ($result as $row) {
                $user = new User();
                $user->setId($row['id']);
                $user->setUserName($row['username']);
                $user->setPassword($row['hashed_password']);
                $user->setEmail($row['email']);

                $allUsers[] = $user;
            }
        }
        return $allUsers;
    }

    /**
     * 
     * @param $userId type Integer
     * @param $password type String
     * @return boolean
     */
    static public function delete($userId, $password) {

        $connection = new Connection();

        $id = $connection->getConnection()->real_escape_string(trim($userId));

        if ($id > 0 && User::verifyById($id, $password)) {

            $sql = $connection->getConnection()->prepare("DELETE FROM Users WHERE id= ?");

            try {

                if (!$sql) {
                    throw new Exception($connection->getConnection()->error);
                } else {
                    $sql->bind_param('i', $id);
                    return $sql->execute();
                }
            } catch (Exception $ex) {
                echo $ex->getMessage();
            }
        }
        return;
    }

    /**
     * 
     * @param $userId type Integer
     * @param $password type String
     * @return boolean
     */
    static public function verifyById($userId, $password) {

        $connection = new Connection();

        $id = $connection->getConnection()->real_escape_string($userId);

        $sql = "SELECT hashed_password FROM Users WHERE id=$id";

        $result = $connection->querySql($sql);

        $passFromDB = $result->fetch_assoc()['hashed_password'];

        if ($passFromDB != null) {
            return password_verify($password, $passFromDB);
        } else {
            return;
        }
    }

    /**
     * 
     * @param $email type String
     * @param $password type String
     * @return boolean
     */
    static public function verifyByEmail($email, $password) {

        $connection = new Connection();

        $verifyEmail = filter_var($email, FILTER_VALIDATE_EMAIL);

        $sql = "SELECT `hashed_password` FROM `Users` WHERE `email`='$verifyEmail'";

        $result = $connection->querySql($sql);

        $passFromDB = $result->fetch_assoc()['hashed_password'];

        if ($passFromDB != null) {
            return password_verify($password, $passFromDB);
        } else {
            return;
        }
    }

    /**
     * 
     * @param $email type String
     * @return \User
     */
    static public function getByEmail($email) {

        $connection = new Connection();

        $verifyEmail = filter_var($email, FILTER_VALIDATE_EMAIL);

        $sql = "SELECT * FROM `Users` WHERE `email`='$verifyEmail'";

        $result = $connection->getConnection()->query($sql);

        if ($result == true && $result->num_rows == 1) {
            $row = $result->fetch_assoc();
            $user = new User();
            $user->setId($row['id']);
            $user->setUserName($row['username']);
            $user->setPassword($row['hashed_password']);
            $user->setEmail($row['email']);
            return $user;
        }
        return;
    }

    /**
     * 
     * @param $email type String
     * @return boolean
     */
    static public function exists($email) {

        $connection = new Connection();

        $verifyEmail = filter_var($email, FILTER_VALIDATE_EMAIL);

        $sql = "SELECT `email` FROM `Users` WHERE `email` = '$verifyEmail'";

        $result = $connection->querySql($sql);

        if ($result == true && $result->num_rows == 1) {

            $emailChecked = $result->fetch_assoc()['email'];

            return $emailChecked;
        }
        return;
    }

    /**
     * 
     * @param $id type Integer
     * @param $name type String
     * @param $email type String
     * @return boolean
     */
    static public function updateUser($id, $name, $email) {

        $connection = new Connection();

        $id = $connection->getConnection()->real_escape_string(trim($id));
        $user = $connection->getConnection()->real_escape_string(trim($name));
        $email = filter_var($email, FILTER_VALIDATE_EMAIL);

        $sql = "UPDATE `Users` SET `username`= '$user',`email`='$email' WHERE `id` = '$id'";

        $result = $connection->querySql($sql);

        if ($result) {
            return true;
        } else {
            return;
        }
    }

}
