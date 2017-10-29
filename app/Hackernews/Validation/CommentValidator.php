<?php

namespace Hackernews\Validation;

/**
 * Class CommentValidator
 *
 * @package Hackernews\Validation
 */
class CommentValidator
{
    /**
     * @param $title
     * @return bool
     */
    public static function validateBody($body)
    {
        if (empty($body) || strlen($body) > 20000) {
            return false;
        }

        if (!is_string($body)) {
            return false;
        }

        return true;
    }

}