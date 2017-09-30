<?php

namespace Hackernews\Access;

/**
 * Interface IPostAccess
 *
 * @package Hackernews\Access
 */
interface IPostAccess
{
    /**
     * @param String $title
     * @param String $slug
     * @param String $url
     * @param String $domain
     * @param int $userRef
     * @return mixed
     */
    public function createPost(String $title, String $slug, String $url, String $domain, int $userRef);

    /**
     * @param int $id
     * @return mixed
     */
    public function getPostById(int $id);

    /**
     * @param String $slug
     * @return String
     */
    public function getUniqueSlug(String $slug);
}