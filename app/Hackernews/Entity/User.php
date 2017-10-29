<?php

namespace Hackernews\Entity;

use JsonSerializable;

/**
 * Class User
 *
 * @package Hackernews\Entity
 */
class User implements JsonSerializable
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var String
     */
    private $alias;

    /**
     * @var int
     */
    private $karma;

    /**
     * @var String
     */
    private $email;

    /**
     * User constructor.
     *
     * @param int $id
     * @param String $email
     * @param int $karma
     * @param String $alias
     */
    public function __construct($id, $alias, $karma, $email = null)
    {
        $this->id = $id;
        $this->karma = $karma;
        $this->alias = $alias;
        $this->email = $email;
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
    public function getAlias(): String
    {
        return $this->alias;
    }

    /**
     * @param String $alias
     */
    public function setAlias(String $alias)
    {
        $this->alias = $alias;
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
     * @return String
     */
    public function getEmail(): String
    {
        return $this->email;
    }

    /**
     * @param String $email
     */
    public function setEmail(String $email)
    {
        $this->email = $email;
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
        $user = [];

        $user['id'] = $this->id;
        $user['karma'] = $this->karma;
        $user['alias'] = $this->alias;

        if (!empty($user['email'])) {
            $user['email'] = $this->email;
        }

        return $user;
    }
}