<?php

namespace Hackernews\Validation;

/**
 * Class PostValidator
 *
 * @package Hackernews\Validation
 */
class PostValidator
{
    /**
     * @param $title
     * @return bool
     */
    public static function validateTitle($title)
    {
        if (empty($title) || strlen($title) > 255) {
            return false;
        }


        return true;
    }

    /**
     * @param $url
     * @return bool
     */
    public static function validateUrl($url)
    {
        $pattern = '/(?:https?:\/\/)(?:[a-zA-Z0-9.-]+?\.(?:[a-zA-Z])|\d+\.\d+\.\d+\.\d+)/';

        if (empty($url) || strlen($url) > 2083 || preg_match($pattern, $url) != 1) {
            return false;
        }

        return true;
    }

    /**
     * @param $content
     * @return bool
     */
    public static function validateContent($content)
    {
        return !empty($content);
    }
}