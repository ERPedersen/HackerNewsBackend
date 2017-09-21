<?php
/**
 * Created by PhpStorm.
 * User: Ebbe
 * Date: 9/21/2017
 * Time: 12:39 PM
 */

namespace Hackernews\Access;

/**
 * Interface ICreateUser
 *
 * @package Hackernews\Access
 */
interface ICreateUser
{
    /**
     * @param String $email
     * @param String $password
     * @param String $alias
     * @return mixed
     */
    public function createUser(String $email, String $password, String $alias);
}