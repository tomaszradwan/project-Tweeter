<?php

include_once 'Connection.php';

class Tweet {

    private $id = -1;
    private $userId = "";
    private $text = "";
    private $creationDate = "";

    public function setId($id) {
        $this->id = $id;
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

    static public function getById($tweetId) {

        $connection = new Connection();

        $id = $connection->conn->real_escape_string($tweetId);

        $sql = "SELECT * FROM Tweets WHERE id=$id";

        $result = $connection->conn->query($sql);

        if ($result == true && $result->num_rows == 1) {
            $row = $result->fetch_assoc();
            $tweet = new Tweet();
            $tweet->setId($row['id']);
            $tweet->setUserId($row['userId']);
            $tweet->setText($row['text']);
            $tweet->setCreationDate($row['creationDate']);
            return $tweet;
        }
        return null;
    }

    static public function getByUserId($userId) {

        $connection = new Connection();

        $id = $connection->conn->real_escape_string($userId);

        $sql = "SELECT * FROM Tweets WHERE userId=$id";

        $allTweets = array();

        $result = $connection->conn->query($sql);

        if ($result == true && $result->num_rows != 0) {

            foreach ($result as $row) {
                $userTweet = new Tweet();
                $userTweet->id = $row['id'];
                $userTweet->userId = $row['userId'];
                $userTweet->text = $row['text'];
                $userTweet->creationDate = $row['creationDate'];

                $allTweets[] = $userTweet;
            }
        }
        return $allTweets;
    }

    static public function loadAllTweets() {

        $connection = new Connection();

        $sql = "SELECT * FROM Tweets";

        $allTweets = array();

        $result = $connection->conn->query($sql);

        if ($result == true && $result->num_rows != 0) {
            foreach ($result as $row) {
                $tweets = new Tweet();
                $tweets->id = $row['id'];
                $tweets->userId = $row['userId'];
                $tweets->text = $row['text'];
                $tweets->creationDate = $row['creationDate'];

                $allTweets[] = $tweets;
            }
        }
        return $allTweets;
    }

    public function saveToDB() {

        $connection = new Connection();

        if ($this->id == -1) {

            $sql = "INSERT INTO `Tweets`(`userId`, `text`, `creationDate`) VALUES ('$this->userId', '$this->text', '$this->creationDate')";
            $result = $connection->querySql($sql);

            if ($result) {

                $this->id = $connection->conn->insert_id;
                return true;
            }
        }
        return false;
    }

    static public function delete($id) {

        $conection = new Connection();

        $stmt = $conection->conn->prepare("DELETE FROM Tweets WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
    }

}
