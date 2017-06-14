<?php

include_once 'Connection.php';

class Tweet {

    /**
     *
     * @var Integer 
     */
    private $id = -1;

    /**
     *
     * @var Integer 
     */
    private $userId = "";

    /**
     *
     * @var String 
     */
    private $text = "";

    /**
     *
     * @var Integer 
     */
    private $creationDate = "";

    /**
     * 
     * @param type Integer
     */
    public function setId($id) {
        $this->id = $id;
    }

    /**
     * 
     * @param type String
     */
    public function setText($text) {
        $this->text = $text;
    }

    /**
     * 
     * @param type Integer
     */
    public function setUserId($userId) {
        $this->userId = $userId;
    }

    /**
     * 
     * @param type Integer
     */
    public function setCreationDate($creationDate) {
        $this->creationDate = $creationDate;
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
    public function getUserId() {
        return $this->userId;
    }

    /**
     * 
     * @return type
     */
    public function getText() {
        return $this->text;
    }

    /**
     * 
     * @return type
     */
    public function getCreationDate() {
        return $this->creationDate;
    }

    /**
     * 
     * @param type Integer
     * @return \Tweet
     */
    static public function getById($id) {

        $connection = new Connection();

        $id = $connection->getConnection()->real_escape_string($id);

        $sql = "SELECT * FROM Tweets WHERE id=$id";

        $result = $connection->querySql($sql);

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

    /**
     * 
     * @param type Integer
     * @return \Tweet
     */
    static public function getByUserId($userId) {

        $connection = new Connection();

        $id = $connection->getConnection()->real_escape_string($userId);

        $sql = "SELECT * FROM Tweets WHERE userId=$id";

        $allTweets = array();

        $result = $connection->querySql($sql);

        if ($result == true && $result->num_rows > 0) {

            foreach ($result as $row) {
                $userTweet = new Tweet();
                $userTweet->setId($row['id']);
                $userTweet->setUserId($row['userId']);
                $userTweet->setText($row['text']);
                $userTweet->setCreationDate($row['creationDate']);

                $allTweets[] = $userTweet;
            }
        }
        return $allTweets;
    }

    /**
     * 
     * @return \Tweet
     */
    static public function getAll() {

        $connection = new Connection();

        $sql = "SELECT * FROM Tweets";

        $allTweets = array();

        $result = $connection->querySql($sql);

        if ($result == true && $result->num_rows != 0) {
            foreach ($result as $row) {
                $tweets = new Tweet();
                $tweets->setId($row['id']);
                $tweets->setUserId($row['userId']);
                $tweets->setText($row['text']);
                $tweets->setCreationDate($row['creationDate']);

                $allTweets[] = $tweets;
            }
        }
        return $allTweets;
    }

    /**
     * 
     * @return boolean
     */
    public function saveToDB() {

        $connection = new Connection();

        if ($this->id == -1) {

            $sql = "INSERT INTO `Tweets`(`userId`, `text`, `creationDate`) VALUES ('$this->userId', '$this->text', '$this->creationDate')";
            $result = $connection->querySql($sql);

            if ($result) {

                $this->id = $connection->getConnection()->insert_id;

                return true;
            }
        }
        return;
    }

    /**
     * 
     * @param type Integer
     */
    static public function delete($id) {

        $conection = new Connection();

        $stmt = $conection->getConnection()->prepare("DELETE FROM Tweets WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

}
