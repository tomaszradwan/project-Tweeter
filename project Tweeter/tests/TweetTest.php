<?php

include "Tweet.php";

class TweetTest extends PHPUnit_Framework_TestCase {

    public function testGetById() {

        $id = 82;

        $result = Tweet::getById($id);

        $this->assertNotEmpty($result);
    }

    public function testGetByUserId() {

        $id = 119;

        $result = Tweet::getByUserId($id);

        $this->assertNotEmpty($result);
    }

    public function testgetAll() {

        $result = Tweet::getAll();

        $this->assertNotEmpty($result);
    }

}

// ALL TESTS ARE GREEN
