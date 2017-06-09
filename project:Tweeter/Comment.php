<?php

include_once 'Connection.php';

class Comment {

    /**
     *
     * @var type 
     */
    private $id = -1;

    /**
     *
     * @var type 
     */
    private $userId = "";

    /**
     *
     * @var type 
     */
    private $tweetId = "";

    /**
     *
     * @var type 
     */
    private $creationDate = "";

    /**
     *
     * @var type 
     */
    private $text = "";

    /**
     * 
     * @param type $id
     */
    public function setId($id) {
        $this->id = $id;
    }

    /**
     * 
     * @param type $id
     */
    public function setUserId($id) {
        $this->userId = $id;
    }

    /**
     * 
     * @param type $tweetId
     */
    public function setTweetId($tweetId) {
        $this->tweetId = $tweetId;
    }

    /**
     * 
     * @param type $date
     */
    public function setCreationDate($date) {
        $this->creationDate = $date;
    }

    /**
     * 
     * @param type $text
     */
    public function setText($text) {
        $this->text = $text;
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
    public function getTweetId() {
        return $this->tweetId;
    }

    /**
     * 
     * @return type
     */
    public function getCreationDate() {
        return $this->creationDate;
    }

    public function getText() {
        return $this->text;
    }

    /**
     * 
     * @param type $commentId
     * @return \Comment
     */
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

    /**
     * 
     * @param type $tweetId
     * @return \Comment
     */
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

    /**
     * 
     * @param type $userId
     * @return \Comment
     */
    static public function getByUserId($userId) {

        $connection = new Connection();

        $id = $connection->conn->real_escape_string(trim($userId));

        $sql = "SELECT * FROM Comment WHERE userId=$id";

        $allComments = array();

        $result = $connection->conn->query($sql);

        if ($result == true && $result->num_rows != 0) {

            foreach ($result as $row) {

                $userComments = new Comment();
                $userComments->setId($row['id']);
                $userComments->setUserId($row['userId']);
                $userComments->setTweetId($row['tweetId']);
                $userComments->setText($row['text']);
                $userComments->setCreationDate($row['creationDate']);

                $allComments[] = $userComments;
            }
        }
        return $allComments;
    }

    /**
     * 
     * @param type $commentId
     */
    static public function delete($commentId) {

        $conection = new Connection();

        $stmt = $conection->conn->prepare("DELETE FROM Comment WHERE id = ?");
        $stmt->bind_param("i", $commentId);
        $stmt->execute();
    }

    /**
     * 
     * @return boolean
     */
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
