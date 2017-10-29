<?php
/**
 * Created by PhpStorm.
 * User: Emil
 * Date: 23-09-2017
 * Time: 15:33
 */

namespace Hackernews\Exceptions;

use Exception;

class DuplicateUserException extends Exception
{
    public function __construct($message = "", $code = 0, \Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}