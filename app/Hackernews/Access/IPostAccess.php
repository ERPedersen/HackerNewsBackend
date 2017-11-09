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
     * @param int $userRef
     * @return mixed
     */
    public function getPostById(int $id, int $userRef);

    /**
     * @param int $userRef
     * @param int $postRef
     * @return mixed
     */
    public function getVote(int $userRef, int $postRef);

    /**
     * @param int $userRef
     * @param int $postRef
     * @return mixed
     */
    public function addUpvote(int $userRef,int $postRef);

    /**
     * @param int $userRef
     * @param int $postRef
     * @return mixed
     */
    public function removeUpvote(int $userRef, int $postRef);

    /**
     * @param int $userRef
     * @param int $postRef
     * @return mixed
     */
    public function addDownvote(int $userRef, int $postRef);

    /**
     * @param int $userRef
     * @param int $postRef
     * @return mixed
     */
    public function removeDownvote(int $userRef, int $postRef);

    /**
     * @param int $userRef
     * @param int $postRef
     * @param int $val
     * @return mixed
     */
    public function changeVote(int $userRef, int $postRef, int $val);
}