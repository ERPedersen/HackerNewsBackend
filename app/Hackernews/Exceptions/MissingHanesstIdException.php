<?php


namespace Hackernews\Exceptions;

use Exception;

/**
 * Class MissingHanesstIdException
 * @package Hackernews\Exceptions
 */
class MissingHanesstIdException extends Exception
{
    /**
     * MissingHanesstIdException constructor.
     * @param string $message
     * @param int $code
     * @param \Throwable|null $previous
     */
    public function __construct($message = "", $code = 0, \Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}