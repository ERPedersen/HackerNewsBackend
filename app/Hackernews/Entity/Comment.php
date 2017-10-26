<?php

namespace Hackernews\Entity;

use JsonSerializable;

/**
 * Class Comment
 *
 * @package Hackernews\Entity
 */
class Comment implements JsonSerializable
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var int
     */
    private $user_ref;

    /**
     * @var int
     */
    private $post_ref;

    /**
     * @var int
     */
    private $comment_ref;

    /**
     * @var String
     */
    private $content;

    /**
     * @var int
     */
    private $karma;

    /**
     * @var bool
     */
    private $spam;

    /**
     * @var String
     */
    private $created_at;

    /**
     * @var User|null
     */
    private $user;

    /**
     * @var int
     */
    private $myVote;

    /**
     * Comment constructor.
     * @param int $id
     * @param int $user_ref
     * @param int $post_ref
     * @param int|null $comment_ref
     * @param String $content
     * @param int $karma
     * @param bool $spam
     * @param String $created_at
     * @param User|null $user
     * @param int $myVote
     */
    public function __construct(int $id, int $user_ref, int $post_ref, int $comment_ref = null, String $content, int $karma, bool $spam, String $created_at, User $user = null, int $myVote = 0)
    {
        $this->id = $id;
        $this->user_ref = $user_ref;
        $this->post_ref = $post_ref;
        $this->comment_ref = $comment_ref;
        $this->content = $content;
        $this->karma = $karma;
        $this->spam = $spam;
        $this->created_at = $created_at;
        $this->user = $user;
        $this->myVote = $myVote;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id)
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getUserRef(): int
    {
        return $this->user_ref;
    }

    /**
     * @param int $user_ref
     */
    public function setUserRef(int $user_ref)
    {
        $this->user_ref = $user_ref;
    }

    /**
     * @return int
     */
    public function getPostRef(): int
    {
        return $this->post_ref;
    }

    /**
     * @param int $post_ref
     */
    public function setPostRef(int $post_ref)
    {
        $this->post_ref = $post_ref;
    }

    /**
     * @return int
     */
    public function getCommentRef(): int
    {
        return $this->comment_ref;
    }

    /**
     * @param int $comment_ref
     */
    public function setCommentRef(int $comment_ref)
    {
        $this->comment_ref = $comment_ref;
    }

    /**
     * @return String
     */
    public function getContent(): String
    {
        return $this->content;
    }

    /**
     * @param String $content
     */
    public function setContent(String $content)
    {
        $this->content = $content;
    }

    /**
     * @return int
     */
    public function getKarma(): int
    {
        return $this->karma;
    }

    /**
     * @param int $karma
     */
    public function setKarma(int $karma)
    {
        $this->karma = $karma;
    }

    /**
     * @return bool
     */
    public function isSpam(): bool
    {
        return $this->spam;
    }

    /**
     * @param bool $spam
     */
    public function setSpam(bool $spam)
    {
        $this->spam = $spam;
    }

    /**
     * @return String
     */
    public function getCreatedAt(): String
    {
        return $this->created_at;
    }

    /**
     * @param String $created_at
     */
    public function setCreatedAt(String $created_at)
    {
        $this->created_at = $created_at;
    }

    /**
     * @return User|null
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param User|null $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return int
     */
    public function getMyVote() {
        return $this->myVote;
    }

    /**
     * @param int $myVote
     */
    public function setMyVote(int $myVote) {
        $this->myVote = $myVote;
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        $comment = [];
        $comment['id'] = $this->id;
        $comment['user_ref'] = $this->user_ref;
        $comment['post_ref'] = $this->post_ref;
        $comment['comment_ref'] = $this->comment_ref;
        $comment['content'] = $this->content;
        $comment['karma'] = $this->karma;
        $comment['spam'] = $this->spam;
        $comment['created_at'] = $this->created_at;

        if (null !== $this->user) {
            $comment['author'] = $this->user;
        }

        $comment['my_vote'] = $this->myVote;

        return $comment;
    }
}