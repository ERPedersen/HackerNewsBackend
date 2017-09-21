<?php

namespace Hackernews\Facade;

use Exception;
use Hackernews\Access\IUserAccess;
use Hackernews\Access\UserAccess;
use Hackernews\Access\ICreateUser;
use Hackernews\Access\CreateUser;

/**
 * Class UserFacade
 *
 * @package Hackernews\Facade
 */
class UserFacade implements IUserFacade
{
    /**
     * @var \Hackernews\Access\IUserAccess
     */
    private $access;
    /**
     * @var \Hackernews\Access\ICreateUser
     */
    private $create;

    /**
     * UserFacade constructor.
     *
     * Will construct the UserFacade with a default UserAccess and CreateUser,
     * but allows for dependency injection if needed-
     *
     * @param IUserAccess|null $access
     * @param ICreateUser|null $create
     */
    function __construct(IUserAccess $access = null, ICreateUser $create = null)
    {
        if ($access === null) {
            $this->access = new UserAccess();
        } else {
            $this->access = $access;
        }

        if ($create === null) {
            $this->create = new CreateUser();
        } else {
            $this->create = $create;
        }
    }

    /**
     * Get User.
     *
     * Returns a user from the access layer.
     *
     * @param String $username
     * @param String $password
     * @throws \Exception
     * @internal param int $id
     * @return \Hackernews\Entity\User
     */
    public function verifyUser(String $username, String $password)
    {
        try {
            return $this->access->verifyUser($username, $password);
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * @param String $email
     * @param String $password
     * @param String $alias
     * @throws Exception
     * @return \Hackernews\Entity\User
     */
    public function createUser(String $email, String $password, String $alias)
    {
        try {
            return $this->create->createUser($email, $password, $alias);
        } catch (Exception $e) {
            throw $e;
        }
    }
}