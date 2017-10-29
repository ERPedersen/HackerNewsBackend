<?php

namespace Hackernews\Validation;

class UserValidator
{
    /**
     * Validates email
     *
     * @param $email
     * @return bool
     */
    public static function validateEmail($email)
    {
        if (empty($email) || ! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return false;
        }

        return true;
    }

    /**
     * Validates password
     *
     * @param $password
     * @return bool
     */
    public static function validatePassword($password)
    {
        if (empty($password)) {
            return false;
        }

        return true;
    }

    /**
     * Validates password
     *
     * @param $alias
     * @return bool
     * @internal param $password
     */
    public static function validateAlias($alias)
    {
        if (empty($alias)) {
            return false;
        }

        return true;
    }
}