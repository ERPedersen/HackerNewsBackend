<?php

namespace Hackernews\Entity;

/**
 * Class User
 *
 * @package Hackernews\Entity
 */
class User
{
    /**
     * @var String
     */
    private $email;

    /**
     * @var String
     */
    private $password;

    /**
     * @var int
     */
    private $karma;

    /**
     * @var String
     */
    private $firstName;

    /**
     * @var String
     */
    private $lastName;

    /**
     * User constructor.
     *
     * @param $email
     * @param $password
     * @param $karma
     * @param $firstName
     * @param $lastName
     */
    public function __construct(String $email, String $password = null, int $karma = 0, String $firstName = null, String $lastName = null)
    {
        $this->email = $email;
        $this->password = $password;
        $this->karma = $karma;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @return mixed
     */
    public function getKarma()
    {
        return $this->karma;
    }

    /**
     * @param mixed $karma
     */
    public function setKarma($karma)
    {
        $this->karma = $karma;
    }

    /**
     * @return mixed
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param mixed $firstName
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    }

    /**
     * @return mixed
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param mixed $lastName
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
    }
}