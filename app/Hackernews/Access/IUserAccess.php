<?php
/**
 * Created by PhpStorm.
 * User: Emil
 * Date: 16-09-2017
 * Time: 21:56
 */

namespace Hackernews\Access;

/**
 * Interface IUserAccess
 *
 * @package Hackernews\Access
 */
interface IUserAccess
{
    /**
     * @param String $username
     * @param String $password
     * @return mixed
     */
    public function verifyUser(String $username, String $password);
}