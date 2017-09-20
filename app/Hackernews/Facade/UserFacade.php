<?php

namespace Hackernews\Facade;

use Exception;
use Hackernews\Access\IUserAccess;
use Hackernews\Access\UserAccess;

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
     * UserFacade constructor.
     *
     * Will construct the UserFacade with a default UserAccess,
     * but allows for dependency injection if needed-
     *
     * @param IUserAccess|null $access
     */
    function __construct(IUserAccess $access = null)
    {
        if ($access === null) {
            $this->access = new UserAccess();
        } else {
            $this->access = $access;
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
}