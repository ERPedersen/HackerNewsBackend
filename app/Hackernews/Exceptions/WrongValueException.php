<?php

namespace Hackernews\Exceptions;

use Exception;

/**
 * Class WrongValueException
 * @package Hackernews\Exceptions
 */
class WrongValueException extends Exception
{
    /**
     * WrongValueException constructor.
     *
     * @param string $message
     * @param int $code
     * @param \Throwable|null $previous
     */
    public function __construct($message = "", $code = 0, \Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}