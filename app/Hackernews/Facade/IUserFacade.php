<?php

namespace Hackernews\Facade;

/**
 * Interface IUserFacade
 *
 * @package Hackernews\Facade
 */
interface IUserFacade
{
    /**
     * @param String $username
     * @param String $password
     * @return mixed
     */
    public function verifyUser(String $username, String $password);

    /**
     * @param String $email
     * @param String $password
     * @param String $alias
     * @return mixed
     */
    public function createUser(String $email, String $password, String $alias);

    /**
     * @param int $id
     * @return mixed
     */
    public function getUserData(int $id);
}