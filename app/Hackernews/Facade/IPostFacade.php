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
     * @param $limit
     * @param $page
     * @param int $userRef
     * @return mixed
     */
    public function getPosts($limit, $page, int $userRef);

    /**
     * @param $slug
     * @param int $userRef
     * @return mixed
     */
    public function getPostBySlug($slug, int $userRef);

    /**
     * @param int $userRef
     * @param int $postRef
     * @return mixed
     */
    public function upvote(int $userRef,int $postRef);
}