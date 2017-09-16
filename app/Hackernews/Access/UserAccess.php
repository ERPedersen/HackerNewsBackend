<?php

namespace Hackernews\Access;

use Exception;
use Hackernews\Entity\User;

/**
 * Class UserAccess
 *
 * @package Hackernews\Access
 */
class UserAccess implements IUserAccess
{
    /**
     * @param String $username
     * @param String $password
     * @return \Hackernews\Entity\User
     * @throws \Exception
     */
    public function verifyUser(String $username, String $password)
    {
        // TODO: Implement DB functionality.
        $_username = "test@test.com";
        $_password = "test";

        if ($username === $_username && $password === $_password) {
            return new User("test@test.com", "test", 666, "John", "Doe");
        } else {
            throw new Exception("Mismatching credentials", 1);
        }
    }
}