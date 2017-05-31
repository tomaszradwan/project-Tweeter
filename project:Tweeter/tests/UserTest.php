<?php

include "User.php";

class UserTest extends PHPUnit_Framework_TestCase {

    public function testloadUserById() {

        $id = 119;

        $result = User::loadUserById($id);

        $this->assertNotEmpty($result);
    }

    public function testloadAllUsers() {

        $result = User::loadAllUsers();

        $this->assertNotEmpty($result);
    }

    public function testpass_verify_by_email() {

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

    public function testloadAllEmails() {

        $email = 'tomek@tomek.pl';

        $result = User::loadAllEmails($email);

        $this->assertSame($email, $result);
    }

    public function testupdateUser() {

        $userId = 119;
        $userName = "tomek radwan";
        $email = "tomek@tomek.pl";

        $result = User::updateUser($userId, $userName, $email);

        $this->assertTrue($result);
    }

}
