<?php

namespace Hackernews\Facade;

/**
 * Interface IPostFacade
 *
 * @package Hackernews\Facade
 */
interface IPostFacade
{
    /**
     * @param String $title
     * @param String $url
     * @param int $userRef
     * @return mixed
     */
    public function createPost(String $title, String $url, int $userRef);

    /**
     * @return mixed
     */
    public function getPosts($limit, $page);

    /**
     * @param $slug
     * @return array
     */
    public function getPostBySlug($slug);
}