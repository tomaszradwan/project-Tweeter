<?php

class Database {

    private $host = "localhost";
    private $user = "root";
    private $password = "coderslab";
    private $db = "twitter";
    public $connection;

    public function __construct() {
        $this->connection = new mysqli($this->host, $this->user, $this->password, $this->db);
    }

    /**
     * 
     * @param type $sql Question to MYSQL
     * @return type result of query
     */
    public function querySql($sql) {
        $result = $this->connection->query($sql);
        return $result;
    }

    public function __destruct() {
        $this->connection->close();
        $this->connection = null;
    }

}

//$new = new Database;
//$new->connection->query("INSERT INTO users(username, email, hashed_password) VALUES ('tomek', 'tomek@mail.com', 'huhu')");
//var_dump($new); 

        
