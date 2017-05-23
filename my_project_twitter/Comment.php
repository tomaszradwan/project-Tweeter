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
        $this->postId = $tweetId;
    }

    public function setCreationDate($date) {
        $this->creationDate = $date;
    }

    public function setText($text) {
        $this->text = $text;
    }

    public function getUserId() {
        return $this->userId;
    }

    public function getTwetId() {
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

        $id = $connection->conn->real_escape_string($idComment);

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

        $id = $connection->conn->real_escape_string($tweetId);

        $sql = "SELECT * FROM Comment WHERE tweetId=$id";

        $result = $connection->conn->query($sql);

        if ($result == true && $result->num_rows == 1) {
            $row = $result->fetch_assoc();
            $loadedComment = new Comment();
            $loadedComment->id = $row['id'];
            $loadedComment->tweetId = $row['tweetId'];
            $loadedComment->userId = $row['userId'];
            $loadedComment->creationDate = $row['creationDate'];
            $loadedComment->text = $row['text'];
            return $loadedComment;
        }
        return null;
    }

    public function deleteComment($idComment) {

        $conection = new Connection();

        $stmt = $conection->conn->prepare("DELETE FROM Comment WHERE id = ?");
        $stmt->bind_param("i", $idComment);
        $stmt->execute();
    }

}
