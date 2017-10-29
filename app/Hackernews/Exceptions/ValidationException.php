<?php

namespace Hackernews\Exceptions;

class ValidationException extends MultiException
{
    public function __construct($message = "", $code = 0, array $errors = [], \Throwable $previous = null)
    {
        parent::__construct($message, $code, $errors, $previous);
    }
}