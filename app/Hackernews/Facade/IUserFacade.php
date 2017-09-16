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
}