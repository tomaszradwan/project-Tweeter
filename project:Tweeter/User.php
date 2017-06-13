<?php

include_once 'Connection.php';

class User {

    /**
     *
     * @var type 
     */
    private $id = -1;

    /**
     *
     * @var type 
     */
    private $username = "";

    /**
     *
     * @var type 
     */
    private $hashedPassword = "";

    /**
     *
     * @var type 
     */
    private $email = "";

    /**
     * 
     * @param type $newPassword
     */
    public function setPassword($newPassword) {
        $this->hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);
    }

    /**
     * 
     * @param type $username
     */
    public function setUserName($username) {
        $this->username = $username;
    }

    /**
     * 
     * @param type $email
     */
    public function setEmail($email) {
        $this->email = $email;
    }

    /**
     * 
     * @param type $id
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
     * @param type $idNumber
     * @return \User
     */
//    nazwa metody ! W klasie Tweet juz poprawiles a tu nie. Zmien na getById
    static public function loadUserById($idNumber) {

        $connection = new Connection();

        $id = $connection->getConnection()->real_escape_string(trim($idNumber));

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
        //nie musisz tak robic. Wystarczy 'return;'
        return null;
    }

    /**
     * 
     * @return type
     */
//    nazwa metody !
    static public function loadAllUsers() {

        $connection = new Connection();

        $sql = "SELECT * FROM Users";

        $allUsers = array();

        $result = $connection->getConnection()->query($sql);

        if ($result == true && $result->num_rows != 0) {
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
     * @param type $idNumber
     * @param type $pass
     * @return boolean
     */
    static public function delete($idNumber, $pass) {

        $connection = new Connection();

        $id = $connection->getConnection()->real_escape_string(trim($idNumber));

        if ($id > 0 && User::passVerifyById($id, $pass)) {

            $sql = $connection->getConnection()->prepare("DELETE FROM Users WHERE id= ?");

            if ($sql) {
                $sql->bind_param('i', $id);
                $sql->execute();
                //wystarczy return $sql->execute();
                return true;
            } else {
                die($connection->getConnection()->error);
                //no z tym Die to ostroznie. To jest bron duzego kalibru. Stosuj Exception zamiast Die
            }
        }
        //bez tego elsa to tez ladnie zadziala. Sam return;
        else {
//            echo "Brak użytkownika o id $id lub podałeś błędne hasło!";
            return false;
        }

    }

    /**
     * 
     * @param type $idNumber
     * @param type $pass
     * @return boolean
     */
    //lepiej nazwij zmienne wprost, $userId i $password
    //nazwa metody verifyById
    static public function passVerifyById($idNumber, $pass) {

        $connection = new Connection();

        $id = $connection->getConnection()->real_escape_string($idNumber);

        $sql = "SELECT hashed_password FROM Users WHERE id=$id";

        $result = $connection->querySql($sql);

        $passFromDB = $result->fetch_assoc()['hashed_password'];

        if ($passFromDB != null) {
            return password_verify($pass, $passFromDB);
        } else {
            return false; //wystarczy return;
        }
    }

    /**
     * 
     * @param type $email
     * @param type $pass
     * @return boolean
     */
    //nazwa metody verifyByEmail
    static public function passVerifyByEmail($email, $pass) {

        $connection = new Connection();

        $verifyEmail = filter_var($email, FILTER_VALIDATE_EMAIL);

        $sql = "SELECT `hashed_password` FROM `Users` WHERE `email`='$verifyEmail'"; //pol i naz tabel nie musisz umieszczac w apostrofach

        $result = $connection->querySql($sql);

        $passFromDB = $result->fetch_assoc()['hashed_password'];

        if ($passFromDB != null) {
            return password_verify($pass, $passFromDB);
        } else {
            return false;
        }
    }

    /**
     * 
     * @param type $email
     * @return \User
     */
    //nazwa metody getByEmail
    static public function loadUserByEmail($email) {

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
        return null; //wystarczy return;
    }

    /**
     * 
     * @param type $email
     * @return boolean
     */
    //nazwa metody lepsza bedzie poprostu exists
    static public function verifyEmailInDB($email) {

        $connection = new Connection();

        $verifyEmail = filter_var($email, FILTER_VALIDATE_EMAIL);

        $sql = "SELECT `email` FROM `Users` WHERE `email` = '$verifyEmail'";

        $result = $connection->querySql($sql);

        if ($result == true && $result->num_rows == 1) {

            $emailChecked = $result->fetch_assoc()['email'];

            return $emailChecked;
        }
        return false; //wystarczy return;
    }

    /**
     * 
     * @param type $userId
     * @param type $userName
     * @param type $userEmail
     * @return boolean
     */
    //nazwa metody update no bo ze user to wiadomow
    //zmienne $id, $name, $email
    static public function updateUser($userId, $userName, $userEmail) {

        $connection = new Connection();

        $id = $connection->getConnection()->real_escape_string(trim($userId));
        $user = $connection->getConnection()->real_escape_string(trim($userName));
        $email = filter_var($userEmail, FILTER_VALIDATE_EMAIL);

        $sql = "UPDATE `Users` SET `username`= '$user',`email`='$email' WHERE `id` = '$id'";

        $result = $connection->querySql($sql);

        if ($result) {
            return true;
        } else {
            return false; //zmiast else daj poprostu return;
        }
    }

}
