<?php
/**
 * Created by PhpStorm.
 * User: bepis
 * Date: 29-10-2017
 * Time: 10:30
 */

namespace Hackernews\Tests;


use Hackernews\Entity\Post;
use Hackernews\Entity\User;
use Hackernews\Facade\PostFacade;
use Mockery;

class PostTest extends \PHPUnit_Framework_TestCase
{
    protected $access;
    protected $facade;

    /**
     * Setting up variables for the tests.
     */
    protected function setUp()
    {
        $this->access = Mockery::mock('Hackernews\Access\IPostAccess');
        $this->facade = new PostFacade($this->access);
    }

    /**
     * Teardown method for test methods.
     */
    public function tearDown()
    {
        Mockery::close();
    }

    /**
     * Testing that we can create a post properly.
     */
    public function testCreatePostSuccessfully()
    {
        $newPost = new Post(1,'Test', 'test-slug', 'Test Content', 'test.biz', 'test.biz', 5, '1920-10-10', '5', false, new User(1, 'test', 5), 0);

        $this->access->shouldReceive('createPost')
            ->times(1)
            ->andReturn(1);

        $this->access->shouldReceive('getPostById')
            ->times(1)
            ->andReturn($newPost);

        $result = $this->facade->createPost('Expose: Using RegEx on HTML will summon the devil',
                                            'https://coolsite.biz/devil-summoning-through-regex',
                                            5);

        self::assertEquals($newPost, $result);

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
