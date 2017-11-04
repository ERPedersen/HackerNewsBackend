<?php
/**
 * Created by PhpStorm.
 * User: denni
 * Date: 11/4/2017
 * Time: 11:00 AM
 */

namespace Hackernews\Exceptions;

use Exception;

/**
 * Class InvalidContentTypeException
 * @package Hackernews\Exceptions
 */
class InvalidContentTypeException extends Exception
{
    /**
     * InvalidContentTypeException constructor.
     * @param string $message
     * @param int $code
     * @param \Throwable|null $previous
     */
    public function __construct($message = "", $code = 0, \Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}