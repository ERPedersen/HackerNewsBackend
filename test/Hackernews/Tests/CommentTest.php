<?php

namespace Hackernews\Tests;

use Exception;
use Hackernews\Entity\Comment;
use Hackernews\Entity\Post;
use Hackernews\Entity\User;
use Hackernews\Exceptions\DuplicatePostException;
use Hackernews\Exceptions\NoUserException;
use Hackernews\Exceptions\ReferenceNotFoundException;
use Hackernews\Facade\CommentFacade;
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
        $this->facade = new CommentFacade($this->access);
        $this->newComment = new Comment(1,69,420,666,'HANS JØRGEN ER FOR VILD', 50000,false, '1920-10-10',new User(1, 'CoolUser', 4), 0);
    }

    /**
     * Teardown method for test methods.
     */
    public function tearDown()
    {
        Mockery::close();
    }

    /**
     * Testing that we can post a comment properly.
     */
    public function testPostCommentSuccessfully()
    {
        $expectation = [
            'comment_id' => 1,
            'comment' => $this->newComment
        ];

        $this->access->shouldReceive('postComment')
            ->times(1)
            ->andReturn(1);

        $this->access->shouldReceive('getCommentById')
            ->times(1)
            ->andReturn($this->newComment);

        $result = $this->facade->postComment(1,69,'HANS JØRGEN ER FOR VILD',666);

        self::assertEquals($expectation, $result);

    }

    /**
     * Testing that we can't post a comment to something that doesn't exist.
     * @expectedException        Exception
     * @expectedExceptionCode    8
     * @expectedExceptionMessage Reference was not found to either post or user.
     */
    public function testPostCommentUnsuccessfully()
    {
        $this->access->shouldReceive('postComment')
            ->times(1)
            ->andThrow(new ReferenceNotFoundException("Reference was not found to either post or user.", 8));


        $this->facade->postComment(1,69,'HANS JØRGEN ER FOR VILD',666);
    }

    /**
     * Testing that we can get all comments from a specific post.
     */
    public function testGetCommentByPostId()
    {

        $expectation = [$this->newComment,$this->newComment,$this->newComment];

        $this->access->shouldReceive('getCommentsByPostId')
            ->times(1)
            ->andReturn($expectation);

        $result = $this->facade->getCommentByPostId(69,1);

        self::assertEquals($expectation, $result);

    }

    /**
     * Testing that we can upvote a comment that we have not upvoted before.
     */
    public function testUpvoteSuccessfullyByAdding()
    {
        $this->access->shouldReceive('getVote')
            ->times(1)
            ->andReturn(0);

        $this->access->shouldReceive('addUpvote')
            ->times(1);

        $this->access->shouldReceive('getCommentById')
            ->times(1)
            ->andReturn($this->newComment);

        $result = $this->facade->upvote(69,420);

        self::assertEquals($this->newComment, $result);
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

        $this->access->shouldReceive('getCommentById')
            ->times(1)
            ->andReturn($this->newComment);

        $result = $this->facade->upvote(69,420);

        self::assertEquals($this->newComment, $result);
    }

    /**
     * Testing that we can upvote a comment that we have downvoted before.
     */
    public function testChangeDownvoteToUpvote()
    {
        $this->access->shouldReceive('getVote')
            ->times(1)
            ->andReturn(-1);

        $this->access->shouldReceive('changeVote')
            ->times(1);

        $this->access->shouldReceive('getCommentById')
            ->times(1)
            ->andReturn($this->newComment);

        $result = $this->facade->upvote(69,420);

        self::assertEquals($this->newComment, $result);
    }

    /**
     * Testing that we can't upvote a comment or user that doesn't exist.
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
     * Testing that we can't remove an upvote from a comment or user that doesn't exist.
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
     * Testing that we can't change a downvote to an upvote from a comment or user that doesn't exist.
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
     * Testing that we can downvote a comment that we have not downvoted before.
     */
    public function testDownvoteSuccessfullyByAdding()
    {
        $this->access->shouldReceive('getVote')
            ->times(1)
            ->andReturn(0);

        $this->access->shouldReceive('addDownvote')
            ->times(1);

        $this->access->shouldReceive('getCommentById')
            ->times(1)
            ->andReturn($this->newComment);

        $result = $this->facade->downvote(69,420);

        self::assertEquals($this->newComment, $result);
    }

    /**
     * Testing that we can remove an downvote from a comment.
     */
    public function testRemoveDownvoteSuccessfully()
    {
        $this->access->shouldReceive('getVote')
            ->times(1)
            ->andReturn(-1);

        $this->access->shouldReceive('removeDownvote')
            ->times(1);

        $this->access->shouldReceive('getCommentById')
            ->times(1)
            ->andReturn($this->newComment);

        $result = $this->facade->downvote(69,420);

        self::assertEquals($this->newComment, $result);
    }

    /**
     * Testing that we can downvote a comment that we have upvoted before.
     */
    public function testChangeUpvoteToDownvote()
    {
        $this->access->shouldReceive('getVote')
            ->times(1)
            ->andReturn(1);

        $this->access->shouldReceive('changeVote')
            ->times(1);

        $this->access->shouldReceive('getCommentById')
            ->times(1)
            ->andReturn($this->newComment);

        $result = $this->facade->downvote(69,420);

        self::assertEquals($this->newComment, $result);
    }

    /**
     * Testing that we can't downvote a comment or user that doesn't exist.
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
     * Testing that we can't remove an downvote from a comment or user that doesn't exist.
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
     * Testing that we can't change an upvote to a downvote from a comment or user that doesn't exist.
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
