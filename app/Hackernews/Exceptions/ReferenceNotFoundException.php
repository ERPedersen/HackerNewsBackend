<?php

namespace Hackernews\Exceptions;

use Exception;

/**
 * Class NoPostsException
 *
 * @package Hackernews\Exceptions
 */
class ReferenceNotFoundException extends Exception
{
    /**
     * ReferenceNotFoundException constructor.
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