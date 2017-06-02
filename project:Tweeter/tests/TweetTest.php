<?php

include "Tweet.php";

class TweetTest extends PHPUnit_Framework_TestCase {

    public function testLoadTweetById() {

        $id = 81;

        $result = Tweet::loadTweetById($id);

        $this->assertNotEmpty($result);
    }

    public function testLoadAllTweetsByUserId() {

        $id = 119;

        $result = Tweet::loadAllTweetsByUserId($id);

        $this->assertNotEmpty($result);
    }

    public function testLoadAllTweets() {

        $result = Tweet::loadAllTweets();

        $this->assertNotEmpty($result);
    }

}

// ALL TESTS ARE GREEN
