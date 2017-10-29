<?php

namespace Hackernews\Tests;

use Dotenv\Dotenv;
use Exception;
use Hackernews\Exceptions\DuplicateUserException;
use Hackernews\Facade\UserFacade;
use Hackernews\Entity\User;
use Hackernews\Services\TokenService;
use Mockery;

/**
 * Class VerificationTest
 *
 * @package Hackernews\Tests
 */
class VerificationTest extends \PHPUnit_Framework_TestCase
{

    protected $access;
    protected $facade;
    protected $tokenService;

    /**
     * Setting up variables for the tests.
     */
    protected function setUp()
    {
        $dotenv = new Dotenv(__DIR__ . '/../../..');
        $dotenv->load();

        $this->access = Mockery::mock('Hackernews\Access\IUserAccess');
        $this->facade = new UserFacade($this->access);
        $this->tokenService = new TokenService();
    }

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
        $newUser = new User(69, 'testuser69', 666, 'test@test.biz');

        $this->access->shouldReceive('verifyUser')
            ->times(1)
            ->andReturn($newUser);

        $result = $this->facade->verifyUser('test@test.biz','test');
        $token = $this->tokenService->decode($result);

        self::assertEquals($this->tokenService->verify($token),true);
    }

    /**
     * Tests for incorrect user login with mocked-out database call.
     * @expectedException     Exception
     * @expectedExceptionCode 1
     * @expectedExceptionMessage Mismatching credentials
     */
    public function testLoginWithIncorrectCredentials()
    {
        $this->access->shouldReceive('verifyUser')
            ->times(1)
            ->andThrow(new Exception("Mismatching credentials", 1));

        $this->facade->verifyUser('badtest@badtest.china','nope');
    }

    /**
     * Tests for creating a user.
     */
    public function testCreateNewUserSuccessfully()
    {
        $newUser = new User(69, 'testuser69', 666, 'test@test.biz');

        $this->access->shouldReceive('createUser')
            ->times(1);

        $this->access->shouldReceive('getUserByEmail')
            ->times(1)
            ->andReturn($newUser);


        $result = $this->facade->createUser('test@test.biz','test','testuser69');
        $token = $this->tokenService->decode($result);

        self::assertEquals($this->tokenService->verify($token),true);


    }

    /**
     * Test for incorrectly creating a new user.
     * @expectedException           Hackernews\Exceptions\DuplicateUserException
     * @expectedExceptionCode       7
     * @expectedExceptionMessage    A user already exists with that e-mail or alias
     */
    public function testCreateNewUserUnsuccessfully()
    {

        $this->access->shouldReceive('createUser')
            ->times(1)
            ->andThrow(new DuplicateUserException("A user already exists with that e-mail or alias", 7));


        $this->facade->createUser('test@test.biz','test','testuser69');
    }

    /**
     * Test for getting a user by their ID.
     */
    public function testGetExistingUserById()
    {
        $user = new User(69, 'testuser69', 666, 'test@test.biz');

        $this->access->shouldReceive('getUserById')
            ->times(1)
            ->andReturn($user);


        $result = $this->facade->getUserData(69);

        self::assertEquals($user, $result);
    }

    /**
     * Test for unsuccessfully getting a user by their ID.
     * @expectedException Exception
     */
    public function testGetExistingUserByIdUnsuccessfully()
    {
        $user = new User(69, 'testuser69', 666, 'test@test.biz');

        $this->access->shouldReceive('getUserById')
            ->times(1)
            ->andThrow(new Exception());

        $this->facade->getUserData(420);
    }

}