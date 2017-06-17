<?php

include "User.php";

class UserTest extends PHPUnit_Framework_TestCase {

    public function testGetById() {

        $id = 119;

        $result = User::getById($id);

        $this->assertNotEmpty($result);
    }

    public function testGetAll() {

        $result = count(User::getAll());

        $expectedValue = '4';

        $this->assertEquals($expectedValue, $result);
    }

    public function testVerifyByEmail() {

        $email = 'tomek@tomek.pl';
        $pass = "pass123";

        $result = User::verifyByEmail($email, $pass);

        $this->assertTrue($result);
    }

    public function testGetByEmail() {

        $email = 'tomek@tomek.pl';

        $result = User::getByEmail($email);

        $this->assertNotEmpty($result);
    }

    public function testExists() {

        $email = 'tomek@tomek.pl';

        $result = User::exists($email);

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
        $user->setEmail("test1@tesfst.pl");

        $result = $user->saveToDB();

        $this->assertTrue($result);
    }

}

// ALL TESTS ARE GREEN
