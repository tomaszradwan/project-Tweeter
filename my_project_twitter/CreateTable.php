<?php

include 'Connection.php';

$connection = new Connection();

$sqlArrayCreateTable = array(
    "CREATE TABLE `Users` ( `id` INT NOT NULL AUTO_INCREMENT , 
        `username` VARCHAR(255) NOT NULL , 
        `email` VARCHAR(255) NOT NULL , 
        `hashed_password` VARCHAR(255) NOT NULL , 
        PRIMARY KEY (`id`), 
        UNIQUE (`email`)) ENGINE = InnoDB
        ",
    "CREATE TABLE `Tweets` ( `id` INT NOT NULL AUTO_INCREMENT , 
        `userId` INT NOT NULL , 
        `text` VARCHAR(255) NOT NULL , 
        `creationDate` DATE NOT NULL , 
        PRIMARY KEY (`id`), 
        FOREIGN KEY (`userId`)
        REFERENCES Users(`id`) ON DELETE CASCADE) ENGINE = InnoDB
        ");



foreach ($sqlArrayCreateTable as $value) {
    $result = $connection->querySql($value);
}

if ($result) {
    die("Tables created!<br/>");
} else {
    die("Tables already exists!<br/>");
}








