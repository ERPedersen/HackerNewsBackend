<?php

namespace Hackernews\Services;

/**
 * Class UrlService
 *
 * @package Hackernews\Services
 */
class UrlService
{
    /**
     * @param $title
     * @return mixed|string
     */
    public static function getSlug($title)
    {
        $title = preg_replace('/^[a-zA-Z\d]+$/', '', $title);
        $title = strtolower($title);
        $title = str_replace(" ", "-", $title);
        $title = urlencode($title);
        return $title;
    }

    /**
     * @param $url
     * @return mixed
     */
    public static function getDomain($url)
    {
        $parts = parse_url($url);

        return $parts['host'];
    }
}