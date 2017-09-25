<?php

namespace Hackernews\Tests;

use Hackernews\Facade\UserFacade;
use Hackernews\Entity\User;
use Exception;

/**
 * Class VerificationTest
 *
 * @package Hackernews\Tests
 */
class VerificationTest extends \PHPUnit_Framework_TestCase
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
     * @expectedException        Exception
     * @expectedExceptionMessage Mismatching credentials
     */
    public function testLoginWithWrongPassword()
    {
        $this->facade = new UserFacade();


        $this->facade->verifyUser('test@test.com','hey');

    }

    /**
     * Tests for incorrect login with nonexistent user.
     * @expectedException        Exception
     * @expectedExceptionMessage Mismatching credentials
     */
    public function testLoginWithWrongUsername()
    {
        $this->facade = new UserFacade();


        $this->facade->verifyUser('balls@balls.ru','test');

    }


}
