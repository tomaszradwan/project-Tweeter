<?php

include_once 'Connection.php';

class Comment {

    private $id;
    private $userId;
    private $tweetId;
    private $creationDate;
    private $text;

    public function __construct() {
        $this->id = -1;
        $this->tweetId = "";
        $this->userId = "";
        $this->creationDate = "";
        $this->text = "";
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

    static public function loadCommentById($idComment) {

        $connection = new Connection();

        $id = $connection->conn->real_escape_string(trim($idComment));

        $sql = "SELECT * FROM Comment WHERE id=$id";

        $result = $connection->conn->query($sql);

        if ($result == true && $result->num_rows == 1) {
            $row = $result->fetch_assoc();
            $loadedComment = new Comment();
            $loadedComment->id = $row['id'];
            $loadedComment->userId = $row['userId'];
            $loadedComment->tweetId = $row['tweetId'];
            $loadedComment->creationDate = $row['creationDate'];
            $loadedComment->text = $row['text'];
            return $loadedComment;
        }
        return null;
    }

    static public function loadCommentByTweetId($tweetId) {

        $connection = new Connection();

        $id = $connection->conn->real_escape_string(trim($tweetId));

        $sql = "SELECT * FROM Comment WHERE tweetId=$id";

        $result = $connection->conn->query($sql);

        $allComments = array();

        if ($result == true && $result->num_rows != 0) {

            foreach ($result as $row) {
                $loadedTweetIdComment = new Comment();
                $loadedTweetIdComment->id = $row['id'];
                $loadedTweetIdComment->userId = $row['userId'];
                $loadedTweetIdComment->tweetId = $row['tweetId'];
                $loadedTweetIdComment->text = $row['text'];
                $loadedTweetIdComment->creationDate = $row['creationDate'];

                $allComments[] = $loadedTweetIdComment;
            }
        }
        return $allComments;
    }

    static public function loadAllCommentByUserId($idUser) {

        $connection = new Connection();

        $id = $connection->conn->real_escape_string(trim($idUser));

        $sql = "SELECT * FROM Comment WHERE userId=$id";

        $allComments = array();

        $result = $connection->conn->query($sql);

        if ($result == true && $result->num_rows != 0) {

            foreach ($result as $row) {
                $loadedUserComment = new Comment();
                $loadedUserComment->id = $row['id'];
                $loadedUserComment->userId = $row['userId'];
                $loadedUserComment->tweetId = $row['tweetId'];
                $loadedUserComment->text = $row['text'];
                $loadedUserComment->creationDate = $row['creationDate'];

                $allComments[] = $loadedUserComment;
            }
        }
        return $allComments;
    }

    static public function deleteComment($idComment) {

        $conection = new Connection();

        $stmt = $conection->conn->prepare("DELETE FROM Comment WHERE id = ?");
        $stmt->bind_param("i", $idComment);
        $stmt->execute();
    }

    public function saveToDB() {

        $connection = new Connection();

        if ($this->id == -1) {

            $sql = "INSERT INTO `Comment`(`userId`, `tweetId`,`creationDate`, `text`) VALUES ($this->userId, '$this->tweetId', '$this->creationDate', '$this->text')";
            $result = $connection->querySql($sql);

            if ($result == true) {

                $this->id = $connection->conn->insert_id;
                return true;
            }
        }
        return false;
    }

}
