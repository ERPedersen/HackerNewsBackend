<?php

namespace Hackernews\Tests;

use Exception;
use Mockery;

/**
 * Class TestTemplate
 *
 * @package Hackernews\Tests
 */
class TestTemplate extends \PHPUnit_Framework_TestCase
{

    protected $access;

    /**
     * Setting up variables for the tests.
     */
    protected function setUp()
    {
        // Use this to mock out certain classes. For example, here we are mocking out the UserAccess class.
        $this->access = Mockery::mock('Hackernews\Access\IUserAccess');
    }

    /**
     * Teardown method for test methods.
     */
    public function tearDown()
    {
        // Close down Mockery when we're done.
        Mockery::close();
    }

    /**
     * Example of a unit test, testing correct behavior.
     */
    public function testLoginWithCorrectCredentials()
    {
        $this->markTestSkipped("Not a test method, used for examples.");

        $newUser = new User(69, 'testuser69', 666, 'test@test.biz');

        // Setting up our mock object, telling it what to expect and what to return.
        $this->access->shouldReceive('verifyUser')
            ->times(1)
            ->andReturn($newUser);

        $result = $this->facade->verifyUser('test@test.biz','test');
        $token = $this->tokenService->decode($result);

        // Assertions.
        self::assertEquals($this->tokenService->verify($token),true);
    }

    /**
     * Example of unit test, testing that something throws an exception.
     * @expectedException        Exception
     * @expectedExceptionCode    1
     * @expectedExceptionMessage Mismatching credentials
     */
    public function testLoginWithIncorrectCredentials()
    {
        $this->markTestSkipped("Not a test method, used for examples.");

        // When testing exceptions, use 'andThrow()', and add the exception details to the PHPDoc above the method.
        $this->access->shouldReceive('verifyUser')
            ->times(1)
            ->andThrow(new Exception("Mismatching credentials", 1));

        $this->facade->verifyUser('badtest@badtest.china','nope');
    }

}