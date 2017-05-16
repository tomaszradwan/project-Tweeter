<?php

class Connection {

    public $conn;

    public function __construct() {

        require 'Parameters.php';

        $this->conn = new mysqli($host, $user, $password, $db);
    }

    /**
     * @param type $sql Question to MYSQL
     * @return type result of query
     */
    public function querySql($sql) {
        $result = $this->conn->query($sql);
        return $result;
    }

    public function __destruct() {
        $this->conn->close();
        $this->conn = null;
    }

}
