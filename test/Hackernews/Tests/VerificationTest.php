<?php

namespace Hackernews\Tests;

use Exception;
use Hackernews\Facade\UserFacade;
use Hackernews\Entity\User;
use Hackernews\Access\UserAccess;
use Hackernews\Services\TokenService;
use Mockery;

/**
 * Class VerificationTest
 *
 * @package Hackernews\Tests
 */
class VerificationTest extends \PHPUnit_Framework_TestCase
{

    /**
     * Teardown method for test methods.
     */
    public function tearDown()
    {
        Mockery::close();
    }

    /**
     * Tests for correct user login with mocked-out database call.
     */
    public function testLoginWithCorrectCredentials()
    {
        $tokenService = new TokenService();
        $access = Mockery::mock('Hackernews\Access\IUserAccess');
        $access->shouldReceive('verifyUser')
            ->times(1)
            ->andReturn(new User(69, 'testuser69', 666, 'test@test.biz'));

        $this->facade = new UserFacade($access);

        $result = $this->facade->verifyUser('test@test.biz','test');
        $token = $tokenService->decode($result);

        self::assertEquals($tokenService->verify($token),true);
    }

    /**
     * Tests for incorrect user login with mocked-out database call.
     * @expectedException     Exception
     * @expectedExceptionCode 1
     * @expectedExceptionMessage Mismatching credentials
     */
    public function testLoginWithIncorrectCredentials()
    {
        $access = Mockery::mock('Hackernews\Access\IUserAccess');
        $access->shouldReceive('verifyUser')
            ->times(1)
            ->andThrow(new Exception("Mismatching credentials", 1));

        $this->facade = new UserFacade($access);

        $this->facade->verifyUser('badtest@badtest.china','nope');
    }


}