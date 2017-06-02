<?php

include "User.php";

class UserTest extends PHPUnit_Framework_TestCase {

    public function testLoadUserById() {

        $id = 119;

        $result = User::loadUserById($id);

        $this->assertNotEmpty($result);
    }

    public function testloadAllUsers() {

        $result = User::loadAllUsers();

        $this->assertNotEmpty($result);
    }

    public function testPass_verify_by_email() {

        $email = 'tomek@tomek.pl';
        $pass = "pass123";

        $result = User::pass_verify_by_email($email, $pass);

        $this->assertTrue($result);
    }

    public function testloadUserByEmail() {

        $email = 'tomek@tomek.pl';

        $result = User::loadUserByEmail($email);

        $this->assertNotEmpty($result);
    }

    public function testLoadAllEmails() {

        $email = 'tomek@tomek.pl';

        $result = User::loadAllEmails($email);

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

        $user->setUserName("test");
        $user->setPassword("test");
        $user->setEmail("test@test.pl");

        $result = $user->saveToDB();

        $this->assertTrue($result);
    }

}

// ALL TESTS ARE GREEN
