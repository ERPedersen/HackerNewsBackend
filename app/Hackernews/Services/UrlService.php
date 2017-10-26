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
	    // replace non letter or digits by -
	    $title = preg_replace('~[^\pL\d]+~u', '-', $title);

	    // transliterate
	    $title = iconv('utf-8', 'us-ascii//TRANSLIT', $title);

	    // remove unwanted characters
	    $title = preg_replace('~[^-\w]+~', '', $title);

	    // trim
	    $title = trim($title, '-');

	    // remove duplicate -
	    $title = preg_replace('~-+~', '-', $title);

	    // lowercase
	    $title = strtolower($title);

	    if (empty($title)) {
		    return 'n-a';
	    }

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