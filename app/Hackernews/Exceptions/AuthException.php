<?php
/**
 * Created by PhpStorm.
 * User: Emil
 * Date: 23-09-2017
 * Time: 15:15
 */

namespace Hackernews\Exceptions;

use Exception;

class AuthException extends Exception
{
    public function __construct($message = "", $code = 0, \Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}