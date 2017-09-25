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
        $_password = password_hash("test",PASSWORD_BCRYPT,['cost' => 10]);

        if ($username === $_username && password_verify($password,$_password)) {
            return new User("test@test.com", "test", 666, "John", "Doe");
        } else {
            throw new Exception("Mismatching credentials", 1);
        }
    }
}