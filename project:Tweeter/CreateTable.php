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
        FOREIGN KEY (`userId`) REFERENCES Users(`id`) ON DELETE CASCADE) ENGINE = InnoDB
        ",
    "CREATE TABLE `Comment` ( `id` INT NOT NULL AUTO_INCREMENT , 
        `userId` INT NOT NULL , 
        `tweetId` INT NOT NULL , 
        `creationDate` DATE NOT NULL , 
        `text` VARCHAR(255),
         PRIMARY KEY (`id`),
         FOREIGN KEY (`userId`) REFERENCES Users(`id`) ON DELETE CASCADE,
         FOREIGN KEY (`tweetId`) REFERENCES Tweets(`id`) ON DELETE CASCADE) ENGINE = InnoDB 
        ",
);



foreach ($sqlArrayCreateTable as $value) {
    $result = $connection->querySql($value);


    if ($result) {
        echo ("Tables created!<br/>");
    } else {
        echo ("Tables already exists!<br/>");
    }
}








