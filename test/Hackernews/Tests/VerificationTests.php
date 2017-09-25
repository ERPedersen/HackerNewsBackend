<?php

namespace Hackernews\Tests;

use Hackernews\Facade\UserFacade;

/**
 * Class VerificationTests
 *
 * @package Hackernews\Tests
 */
class VerificationTests extends \PHPUnit_Framework_TestCase
{

    /**
     * Tests for correct user login.
     */
    public function testLoginWithCorrectCredentials()
    {
        $this->facade = new UserFacade();
        $user = new User("test@test.com", "test", 666, "John", "Doe");


        $result = $this->facade->verifyUser('test@test.com','test');


        self::assertEquals($user,$result);
    }

    /**
     * Tests for incorrect user login with bad password.
     */
    public function testLoginWithWrongPassword()
    {
        $this->facade = new UserFacade();
        $user = new User("test@test.com", "test", 666, "John", "Doe");


        $result = $this->facade->verifyUser('test@test.com','test');


        self::assertEquals($user,$result);
    }

    /**
     * Tests for incorrect login with nonexistent user.
     */
    public function testLoginWithWrongUsername()
    {
        $this->facade = new UserFacade();
        $user = new User("test@test.com", "test", 666, "John", "Doe");


        $result = $this->facade->verifyUser('balls@balls.ru','test');


        self::assertEquals($user,$result);
    }


}
