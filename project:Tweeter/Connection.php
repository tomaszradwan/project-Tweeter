<?php

class Connection {

    /**
     *
     * @var type 
     */
    private $conn;

    public function __construct() {
        /*
         * I put 'Parameters.php' file in constructor because when I located before 
         * class Connection I have an error, variables($host, $user, $password, $db) does not exists 
         * (message: Undefined variable).
         */
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
