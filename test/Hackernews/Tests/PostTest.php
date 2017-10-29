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
use Hackernews\Exceptions\DuplicatePostException;
use Hackernews\Facade\PostFacade;
use Mockery;

class PostTest extends \PHPUnit_Framework_TestCase
{
    protected $access;
    protected $facade;
    protected $newPost;

    /**
     * Setting up variables for the tests.
     */
    protected function setUp()
    {
        $this->access = Mockery::mock('Hackernews\Access\IPostAccess');
        $this->facade = new PostFacade($this->access);
        $this->newPost = new Post(1,'Test', 'test-slug', 'Test Content', 'test.biz', 'test.biz', 5, '1920-10-10', '5', false, new User(1, 'test', 5), 0);
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


        $this->access->shouldReceive('createPost')
            ->times(1)
            ->andReturn(1);

        $this->access->shouldReceive('getPostById')
            ->times(1)
            ->andReturn($this->newPost);

        $result = $this->facade->createPost('Expose: Using RegEx on HTML will summon the devil',
                                            'https://coolsite.biz/devil-summoning-through-regex',
                                            5);

        self::assertEquals($this->newPost, $result);

    }

    /**
     * Example of unit test, testing that something throws an exception.
     * @expectedException        Exception
     * @expectedExceptionCode    7
     * @expectedExceptionMessage This URL has already been posted before. Reposting is not allowed.
     */
    public function testCreateDuplicatePostUnsuccessfully()
    {
        $this->access->shouldReceive('createPost')
            ->times(1)
            ->andReturn(1);

        $this->access->shouldReceive('getPostById')
            ->times(1)
            ->andThrow(new DuplicatePostException("This URL has already been posted before. Reposting is not allowed.", 7));

        $this->facade->createPost('Expose: Using RegEx on HTML will summon the devil',
            'https://coolsite.biz/devil-summoning-through-regex',
            5);
    }
}
