<?php

include_once 'Connection.php';

class Tweet {

    private $id;
    private $userId;
    private $text;
    private $creationDate;

    public function __construct() {
        $this->id = -1;
        $this->userId = "";
        $this->text = "";
        $this->creationDate = "";
    }

    public function setText($text) {
        $this->text = $text;
    }

    public function setUserId($userId) {
        $this->userId = $userId;
    }

    public function setCreationDate($creationDate) {
        $this->creationDate = $creationDate;
    }

    public function getId() {
        return $this->id;
    }

    public function getUserId() {
        return $this->userId;
    }

    public function getText() {
        return $this->text;
    }

    public function getCreationDate() {
        return $this->creationDate;
    }

    static public function loadTweetById($idTweet) {

        $connection = new Connection();

        $id = $connection->conn->real_escape_string($idTweet);

        $sql = "SELECT * FROM Tweets WHERE id=$id";

        $result = $connection->conn->query($sql);

        if ($result == true && $result->num_rows == 1) {
            $row = $result->fetch_assoc();
            $loadedTweet = new Tweet();
            $loadedTweet->id = $row['id'];
            $loadedTweet->userId = $row['userId'];
            $loadedTweet->text = $row['text'];
            $loadedTweet->creationDate = $row['creationDate'];
            return $loadedTweet;
        }
        return null;
    }

    static public function loadAllTweetsByUserId($idUser) {

        $connection = new Connection();
        $id = $connection->conn->real_escape_string($idUser);

        $sql = "SELECT * FROM Tweets WHERE userId=$id";

        $allTweets = array();

        $result = $connection->conn->query($sql);

        if ($result == true && $result->num_rows != 0) {

            foreach ($result as $row) {
                $loadedUserTweet = new Tweet();
                $loadedUserTweet->id = $row['id'];
                $loadedUserTweet->userId = $row['userId'];
                $loadedUserTweet->text = $row['text'];
                $loadedUserTweet->creationDate = $row['creationDate'];

                $allTweets[] = $loadedUserTweet;
            }
        }
        return $allTweets;
    }

    static public function loadAllTweets() {

        $connection = new Connection();

        $sql = "SELECT * FROM Tweets";

        $ret = [];

        $result = $connection->conn->query($sql);

        if ($result == true && $result->num_rows != 0) {
            foreach ($result as $row) {
                $loadedTweet = new Tweet();
                $loadedTweet->id = $row['id'];
                $loadedTweet->userId = $row['userId'];
                $loadedTweet->text = $row['text'];
                $loadedTweet->creationDate = $row['creationDate'];

                $ret[] = $loadedTweet;
            }
        }
        return $ret;
    }

    public function saveToDB() {

        $connection = new Connection();

        if ($this->id == -1) {

            $sql = "INSERT INTO `Tweets`(`userId`, `text`, `creationDate`) VALUES ('$this->userId', '$this->text', '$this->creationDate')";
            $result = $connection->querySql($sql);

            if ($result == true) {

                $this->id = $connection->conn->insert_id;
                return true;
            }
        }
        return false;
    }

    public function deleteTweet($id) {

        $conection = new Connection();

        $stmt = $conection->conn->prepare("DELETE FROM Tweets WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
    }

}
