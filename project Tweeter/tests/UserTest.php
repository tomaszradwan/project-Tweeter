<?php

include "User.php";

class UserTest extends PHPUnit_Framework_TestCase {

    public function testLoadUserById() {

        $id = 119;

        $result = User::loadUserById($id);

        $this->assertNotEmpty($result);
    }

    public function testLoadAllUsers() {

        $result = count(User::loadAllUsers());

        $expectedValue = '16';

        $this->assertEquals($expectedValue, $result);
    }

    public function testPassVerifyByEmail() {

        $email = 'tomek@tomek.pl';
        $pass = "pass123";

        $result = User::passVerifyByEmail($email, $pass);

        $this->assertTrue($result);
    }

    public function testLoadUserByEmail() {

        $email = 'tomek@tomek.pl';

        $result = User::loadUserByEmail($email);

        $this->assertNotEmpty($result);
    }

    public function testVerifyEmailInDB() {

        $email = 'tomek@tomek.pl';

        $result = User::verifyEmailInDB($email);

        $this->assertSame($email, $result);
    }

    public function testUpdateUser() {

        $userId = 119;
        $userName = "tomek radwan";
        $email = "tomek@tomek.pl";

        $result = User::updateUser($userId, $userName, $email);

        $this->assertTrue($result);
    }

    public function testSaveToDB() {

        $user = new User();

        $user->setUserName("test1");
        $user->setPassword("test2");
        $user->setEmail("test1@tefst.pl");

        $result = $user->saveToDB();

        $this->assertTrue($result);
    }

}

// ALL TESTS ARE GREEN
