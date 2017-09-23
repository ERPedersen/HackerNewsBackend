<?php

namespace Hackernews\Exceptions;

use Exception;

/**
 * Class DuplicatePostException
 *
 * @package Hackernews\Exceptions
 */
class DuplicatePostException extends Exception
{
    /**
     * DuplicatePostException constructor.
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