<?php

class Connection {

    /**
     *
     * @var type 
     */
    private $conn;

    public function __construct() {

        require 'Parameters.php';

        $this->conn = new mysqli($host, $user, $password, $db);
    }

    /**
     * 
     * @return type
     */
    public function getConnection() {
        return $this->conn;
    }

    /**
     * @param type $sql Question to MYSQL
     * @return type result of query
     */
    public function querySql($sql) {
        return $this->conn->query($sql);
    }

    public function __destruct() {
        $this->conn->close();
        $this->conn = null;
    }

}
