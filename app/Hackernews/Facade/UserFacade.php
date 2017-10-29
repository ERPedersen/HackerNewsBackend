<?php

namespace Hackernews\Facade;

use Exception;
use Hackernews\Access\IUserAccess;
use Hackernews\Access\UserAccess;
use Hackernews\Access\ICreateUser;
use Hackernews\Access\CreateUser;
use Hackernews\Exceptions\DuplicateUserException;
use Hackernews\Services\TokenService;

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
     * Will construct the UserFacade with a default UserAccess and CreateUser,
     * but allows for dependency injection if needed-
     *
     * @param \Hackernews\Access\IUserAccess|null $access
     */
    function __construct(IUserAccess $access = null)
    {
        $this->access = $access ? $access : new UserAccess();
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
     * @return String
     */
    public function verifyUser(String $username, String $password)
    {
        try {
            $user = $this->access->verifyUser($username, $password);
            $tokenService = new TokenService();

            return $tokenService->sign([
                'id' => $user->getId(),
                'email' => $user->getEmail(),
                'alias' => $user->getAlias(),
                'karma' => $user->getKarma(),
            ]);
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * @param String $email
     * @param String $password
     * @param String $alias
     * @return mixed|string
     * @throws Exception
     */
    public function createUser(String $email, String $password, String $alias)
    {
        try {
            $this->access->createUser($email, $password, $alias);
        } catch (Exception $e) {
            throw $e;
        }

        try {
            $user = $this->access->getUserByEmail($email);
            $tokenService = new TokenService();

            return $tokenService->sign([
                'id' => $user->getId(),
                'email' => $user->getEmail(),
                'alias' => $user->getAlias(),
                'karma' => $user->getKarma(),
            ]);
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * @param int $id
     * @return \Hackernews\Entity\User
     * @throws Exception
     */
    public function getUserData(int $id) {
        try {
            $user = $this->access->getUserById($id);

            return $user;
        } catch (Exception $e) {
            throw $e;
        }
    }
}