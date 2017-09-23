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
    private $id;

    private $slug;

    private $title;

    private $url;

    private $domain;

    private $karma;

    private $userRef;

    private $spam;

    /**
     * Post constructor.
     *
     * @param int $id
     * @param String $title
     * @param String $slug
     * @param String $url
     * @param String $domain
     * @param String $karma
     * @param String $userRef
     * @param bool $spam
     */
    public function __construct(
        int $id,
        String $title,
        String $slug,
        String $url,
        String $domain,
        String $karma,
        String $userRef = null,
        $spam = false
    ) {
        $this->id = $id;
        $this->title = $title;
        $this->slug = $slug;
        $this->url = $url;
        $this->domain = $domain;
        $this->karma = $karma;
        $this->userRef = $userRef;
        $this->spam = $spam;
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
        $post['url'] = $this->url;
        $post['domain'] = $this->domain;
        $post['karma'] = $this->karma;
        $post['spam'] = $this->spam;

        return $post;
    }
}
