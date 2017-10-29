<?php

namespace Hackernews\Exceptions;

use Exception;

class MultiException extends Exception
{
    private $errors;

    public function __construct($message = "", $code = 0, $errors = [], \Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->errors = $errors;
    }

    public function getErrors()
    {
        return $this->errors;
    }
}