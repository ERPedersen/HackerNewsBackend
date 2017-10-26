<?php

namespace Hackernews\Exceptions;

use Exception;

/**
 * Class UpdateException
 * @package Hackernews\Exceptions
 */
class UpdateException extends Exception
{
    /**
     * UpdateException constructor.
     * @param string $message
     * @param int $code
     * @param array $errors
     * @param \Throwable|null $previous
     */
    public function __construct($message = "", $code = 0, array $errors = [], \Throwable $previous = null)
    {
        parent::__construct($message, $code, $errors, $previous);
    }
}