<?php

namespace Hackernews\Entity;

use JsonSerializable;

/**
 * Class Post
 *
 * @package Hackernews\Entity
 */
class Post implements JsonSerializable
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var String
     */
    private $slug;

    /**
     * @var String
     */
    private $content;

    /**
     * @var String
     */
    private $title;

    /**
     * @var String
     */
    private $url;

    /**
     * @var String
     */
    private $domain;

    /**
     * @var String
     */
    private $karma;

    /**
     * @var String
     */
    private $createdAt;

    /**
     * @var String
     */
    private $userRef;

    /**
     * @var bool
     */
    private $spam;

    /**
     * @var User
     */
    private $user;

    /**
     * Post constructor.
     *
     * @param int $id
     * @param String $title
     * @param String $slug
     * @param String $content
     * @param String $url
     * @param String $domain
     * @param String $karma
     * @param String $createdAt
     * @param String $userRef
     * @param bool $spam
     * @param User $user
     */
    public function __construct(
        int $id,
        String $title,
        String $slug,
        String $content = null,
        String $url = null,
        String $domain = null,
        String $karma,
        String $createdAt,
        String $userRef = null,
        bool $spam = false,
        User $user = null
    )
    {
        $this->id = $id;
        $this->title = $title;
        $this->slug = $slug;
        $this->content = $content;
        $this->url = $url;
        $this->domain = $domain;
        $this->karma = $karma;
        $this->createdAt = $createdAt;
        $this->userRef = $userRef;
        $this->spam = $spam;
        $this->user = $user;
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
     * @return String
     */
    public function getTitle(): String
    {
        return $this->title;
    }

    /**
     * @param String $title
     */
    public function setTitle(String $title)
    {
        $this->title = $title;
    }

    /**
     * @return String
     */
    public function getSlug(): String
    {
        return $this->slug;
    }

    /**
     * @param String $slug
     */
    public function setSlug(String $slug)
    {
        $this->slug = $slug;
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
     * @return String
     */
    public function getUrl(): String
    {
        return $this->url;
    }

    /**
     * @param String $url
     */
    public function setUrl(String $url)
    {
        $this->url = $url;
    }

    /**
     * @return String
     */
    public function getDomain(): String
    {
        return $this->domain;
    }

    /**
     * @param String $domain
     */
    public function setDomain(String $domain)
    {
        $this->domain = $domain;
    }

    /**
     * @return String
     */
    public function getKarma(): String
    {
        return $this->karma;
    }

    /**
     * @param String $karma
     */
    public function setKarma(String $karma)
    {
        $this->karma = $karma;
    }

    /**
     * @return String
     */
    public function getCreatedAt(): String
    {
        return $this->createdAt;
    }

    /**
     * @param String $createdAt
     */
    public function setCreatedAt(String $createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return String
     */
    public function getUserRef(): String
    {
        return $this->userRef;
    }

    /**
     * @param String $userRef
     */
    public function setUserRef(String $userRef)
    {
        $this->userRef = $userRef;
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
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser(User $user)
    {
        $this->user = $user;
    }

    /**
     * Specify data which should be serialized to JSON
     *
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    function jsonSerialize()
    {
        $post = [];
        $post['id'] = $this->id;
        $post['title'] = $this->title;
        $post['slug'] = $this->slug;
        $post['karma'] = $this->karma;
        $post['created_at'] = $this->createdAt;
        $post['spam'] = $this->spam;

        if (null !== $this->domain) {
            $post['domain'] = $this->domain;
        }

        if (null !== $this->url) {
            $post['url'] = $this->url;
        }

        if (null !== $this->content) {
            $post['content'] = $this->content;
        }

        if (null !== $this->user) {
            $post['author'] = $this->user;
        }

        return $post;
    }
}
