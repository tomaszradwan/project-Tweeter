<?php

include_once 'Connection.php';

class Comment {

    private $id = -1;
    private $userId = "";
    private $tweetId = "";
    private $creationDate = "";
    private $text = "";

    public function setId($id) {
        $this->id = $id;
    }

    public function setUserId($id) {
        $this->userId = $id;
    }

    public function setTweetId($tweetId) {
        $this->tweetId = $tweetId;
    }

    public function setCreationDate($date) {
        $this->creationDate = $date;
    }

    public function setText($text) {
        $this->text = $text;
    }

    public function getId() {
        return $this->id;
    }

    public function getUserId() {
        return $this->userId;
    }

    public function getTweetId() {
        return $this->tweetId;
    }

    public function getCreationDate() {
        return $this->creationDate;
    }

    public function getText() {
        return $this->text;
    }

    static public function getById($commentId) {

        $connection = new Connection();

        $id = $connection->conn->real_escape_string(trim($commentId));

        $sql = "SELECT * FROM Comment WHERE id=$id";

        $result = $connection->conn->query($sql);

        if ($result == true && $result->num_rows == 1) {

            $row = $result->fetch_assoc();

            $comment = new Comment();
            $comment->setId($row['id']);
            $comment->setUserId($row['userId']);
            $comment->setTweetId($row['tweetId']);
            $comment->setCreationDate($row['creationDate']);
            $comment->setText($row['text']);
            return $comment;
        }
        return;
    }

    static public function getTweetById($tweetId) {

        $connection = new Connection();

        $id = $connection->conn->real_escape_string(trim($tweetId));

        $sql = "SELECT * FROM Comment WHERE tweetId=$id";

        $result = $connection->conn->query($sql);

        $allComments = array();

        if ($result == true && $result->num_rows != 0) {

            foreach ($result as $row) {
                $comment = new Comment();
                $comment->setId($row['id']);
                $comment->setUserId($row['userId']);
                $comment->setTweetId($row['tweetId']);
                $comment->setText($row['text']);
                $comment->setCreationDate($row['creationDate']);

                $allComments[] = $comment;
            }
        }
        return $allComments;
    }

    static public function getByUserId($userId) {

        $connection = new Connection();

        $id = $connection->conn->real_escape_string(trim($userId));

        $sql = "SELECT * FROM Comment WHERE userId=$id";

        $allComments = array();

        $result = $connection->conn->query($sql);

        if ($result == true && $result->num_rows != 0) {

            foreach ($result as $row) {

                $loadedUserComment = new Comment();
                $loadedUserComment->setId($row['id']);
                $loadedUserComment->setUserId($row['userId']);
                $loadedUserComment->setTweetId($row['tweetId']);
                $loadedUserComment->setText($row['text']);
                $loadedUserComment->setCreationDate($row['creationDate']);

                $allComments[] = $loadedUserComment;
            }
        }
        return $allComments;
    }

    static public function delete($commentId) {

        $conection = new Connection();

        $stmt = $conection->conn->prepare("DELETE FROM Comment WHERE id = ?");
        $stmt->bind_param("i", $commentId);
        $stmt->execute();
    }

    public function saveToDB() {

        $connection = new Connection();

        if ($this->id == -1) {

            $sql = "INSERT INTO `Comment`(`userId`, `tweetId`,`creationDate`, `text`) VALUES ($this->userId, '$this->tweetId', '$this->creationDate', '$this->text')";
            $result = $connection->querySql($sql);

            if ($result) {

                $this->id = $connection->conn->insert_id;
                return true;
            }
        }
        return false;
    }

}
