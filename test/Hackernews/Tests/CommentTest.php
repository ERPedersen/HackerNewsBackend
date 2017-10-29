<?php

namespace Hackernews\Tests;

use Exception;
use Hackernews\Entity\Post;
use Hackernews\Entity\User;
use Hackernews\Exceptions\DuplicatePostException;
use Hackernews\Exceptions\NoUserException;
use Hackernews\Facade\PostFacade;
use Mockery;

class CommentTest extends \PHPUnit_Framework_TestCase
{
    protected $access;
    protected $facade;
    protected $newComment;

    /**
     * Setting up variables for the tests.
     */
    protected function setUp()
    {
        $this->access = Mockery::mock('Hackernews\Access\ICommentAccess');
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
     * Testing that we can't create a duplicate post.
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

    /**
     * Testing that we can create a story properly.
     */
    public function testCreateStorySuccessfully()
    {


        $this->access->shouldReceive('createStory')
            ->times(1)
            ->andReturn(1);

        $this->access->shouldReceive('getPostById')
            ->times(1)
            ->andReturn($this->newPost);

        $result = $this->facade->createStory('Expose: Using RegEx on HTML will summon the devil',
            'https://coolsite.biz/devil-summoning-through-regex',
            5);

        self::assertEquals($this->newPost, $result);

    }

    /**
     * Testing that we can't create a duplicate story.
     * @expectedException        Exception
     * @expectedExceptionCode    7
     * @expectedExceptionMessage This URL has already been posted before. Reposting is not allowed.
     */
    public function testCreateDuplicateStoryUnsuccessfully()
    {
        $this->access->shouldReceive('createStory')
            ->times(1)
            ->andReturn(1);

        $this->access->shouldReceive('getPostById')
            ->times(1)
            ->andThrow(new DuplicatePostException("This URL has already been posted before. Reposting is not allowed.", 7));

        $this->facade->createStory('Expose: Using RegEx on HTML will summon the devil',
            'https://coolsite.biz/devil-summoning-through-regex',
            5);
    }

    /**
     * Testing that we can get a list of posts.
     */
    public function testGetPosts()
    {
        $postArray = [$this->newPost,$this->newPost,$this->newPost];

        $this->access->shouldReceive('getPosts')
            ->times(1)
            ->andReturn($postArray);

        $result = $this->facade->getPosts(5,1,69);

        self::assertEquals($postArray, $result);

    }

    /**
     * Testing that we can get posts by their slug.
     */
    public function testGetPostsBySlug()
    {
        $postArray = [$this->newPost,$this->newPost,$this->newPost];

        $this->access->shouldReceive('getPostBySlug')
            ->times(1)
            ->andReturn($postArray);

        $result = $this->facade->getPostBySlug('test-slug',69);

        self::assertEquals($postArray, $result);
    }

    /**
     * Testing that we can upvote a post that we have not upvoted before.
     */
    public function testUpvoteSuccessfullyByAdding()
    {
        $this->access->shouldReceive('getVote')
            ->times(1)
            ->andReturn(0);

        $this->access->shouldReceive('addUpvote')
            ->times(1);

        $this->access->shouldReceive('getPostById')
            ->times(1)
            ->andReturn($this->newPost);

        $result = $this->facade->upvote(69,420);

        self::assertEquals($this->newPost, $result);
    }

    /**
     * Testing that we can remove an upvote from a post.
     */
    public function testRemoveUpvoteSuccessfully()
    {
        $this->access->shouldReceive('getVote')
            ->times(1)
            ->andReturn(1);

        $this->access->shouldReceive('removeUpvote')
            ->times(1);

        $this->access->shouldReceive('getPostById')
            ->times(1)
            ->andReturn($this->newPost);

        $result = $this->facade->upvote(69,420);

        self::assertEquals($this->newPost, $result);
    }

    /**
     * Testing that we can upvote a post that we have downvoted before.
     */
    public function testChangeDownvoteToUpvote()
    {
        $this->access->shouldReceive('getVote')
            ->times(1)
            ->andReturn(-1);

        $this->access->shouldReceive('changeVote')
            ->times(1);

        $this->access->shouldReceive('getPostById')
            ->times(1)
            ->andReturn($this->newPost);

        $result = $this->facade->upvote(69,420);

        self::assertEquals($this->newPost, $result);
    }

    /**
     * Testing that we can't upvote a post or user that doesn't exist.
     * @expectedException        Exception
     * @expectedExceptionMessage The User doesn't exist!
     */
    public function testUpvoteByAddingUnsuccessfully()
    {
        $this->access->shouldReceive('getVote')
            ->times(1)
            ->andReturn(0);

        $this->access->shouldReceive('addUpvote')
            ->times(1)
            ->andThrow(new NoUserException("The User doesn't exist!"));


        $this->facade->upvote(69,420);
    }

    /**
     * Testing that we can't remove an upvote from a post or user that doesn't exist.
     * @expectedException        Exception
     * @expectedExceptionMessage The User doesn't exist!
     */
    public function testRemoveUpvoteUnsuccessfully()
    {
        $this->access->shouldReceive('getVote')
            ->times(1)
            ->andReturn(1);

        $this->access->shouldReceive('removeUpvote')
            ->times(1)
            ->andThrow(new NoUserException("The User doesn't exist!"));


        $this->facade->upvote(69,420);
    }

    /**
     * Testing that we can't change a downvote to an upvote from a post or user that doesn't exist.
     * @expectedException        Exception
     * @expectedExceptionMessage The User doesn't exist!
     */
    public function testChangeDownvoteToUpvoteUnsuccessfully()
    {
        $this->access->shouldReceive('getVote')
            ->times(1)
            ->andReturn(-1);

        $this->access->shouldReceive('changeVote')
            ->times(1)
            ->andThrow(new NoUserException("The User doesn't exist!"));


        $this->facade->upvote(69,420);
    }

    /**
     * Testing that we can downvote a post that we have not downvoted before.
     */
    public function testDownvoteSuccessfullyByAdding()
    {
        $this->access->shouldReceive('getVote')
            ->times(1)
            ->andReturn(0);

        $this->access->shouldReceive('addDownvote')
            ->times(1);

        $this->access->shouldReceive('getPostById')
            ->times(1)
            ->andReturn($this->newPost);

        $result = $this->facade->downvote(69,420);

        self::assertEquals($this->newPost, $result);
    }

    /**
     * Testing that we can remove an downvote from a post.
     */
    public function testRemoveDownvoteSuccessfully()
    {
        $this->access->shouldReceive('getVote')
            ->times(1)
            ->andReturn(-1);

        $this->access->shouldReceive('removeDownvote')
            ->times(1);

        $this->access->shouldReceive('getPostById')
            ->times(1)
            ->andReturn($this->newPost);

        $result = $this->facade->downvote(69,420);

        self::assertEquals($this->newPost, $result);
    }

    /**
     * Testing that we can downvote a post that we have upvoted before.
     */
    public function testChangeUpvoteToDownvote()
    {
        $this->access->shouldReceive('getVote')
            ->times(1)
            ->andReturn(1);

        $this->access->shouldReceive('changeVote')
            ->times(1);

        $this->access->shouldReceive('getPostById')
            ->times(1)
            ->andReturn($this->newPost);

        $result = $this->facade->downvote(69,420);

        self::assertEquals($this->newPost, $result);
    }

    /**
     * Testing that we can't downvote a post or user that doesn't exist.
     * @expectedException        Exception
     * @expectedExceptionMessage The User doesn't exist!
     */
    public function testDownvoteByAddingUnsuccessfully()
    {
        $this->access->shouldReceive('getVote')
            ->times(1)
            ->andReturn(0);

        $this->access->shouldReceive('addDownvote')
            ->times(1)
            ->andThrow(new NoUserException("The User doesn't exist!"));


        $this->facade->downvote(69,420);
    }

    /**
     * Testing that we can't remove an downvote from a post or user that doesn't exist.
     * @expectedException        Exception
     * @expectedExceptionMessage The User doesn't exist!
     */
    public function testRemoveDownvoteUnsuccessfully()
    {
        $this->access->shouldReceive('getVote')
            ->times(1)
            ->andReturn(-1);

        $this->access->shouldReceive('removeDownvote')
            ->times(1)
            ->andThrow(new NoUserException("The User doesn't exist!"));


        $this->facade->downvote(69,420);
    }

    /**
     * Testing that we can't change an upvote to a downvote from a post or user that doesn't exist.
     * @expectedException        Exception
     * @expectedExceptionMessage The User doesn't exist!
     */
    public function testChangeUpvoteToDownvoteUnsuccessfully()
    {
        $this->access->shouldReceive('getVote')
            ->times(1)
            ->andReturn(1);

        $this->access->shouldReceive('changeVote')
            ->times(1)
            ->andThrow(new NoUserException("The User doesn't exist!"));


        $this->facade->downvote(69,420);
    }

}
