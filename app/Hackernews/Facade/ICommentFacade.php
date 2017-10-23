<?php
/**
 * Created by PhpStorm.
 * User: Ebbe
 * Date: 9/24/2017
 * Time: 12:38 PM
 */

namespace Hackernews\Facade;

/**
 * Interface ICommentFacade
 * @package Hackernews\Facade
 */
interface ICommentFacade
{
    /**
     * @param int $postRef
     * @param int $limit
     * @param int $page
     * @return mixed
     */
    public function getCommentByPostId(int $postRef, int $limit, int $page);

    /**
     * @param int $userRef
     * @param int $postRef
     * @param string $content
     * @param int $commentRef
     * @return mixed
     */
    public function postComment(int $userRef, int $postRef, string $content, int $commentRef);

    /**
     * @param int $userRef
     * @param int $commentRef
     * @return mixed
     */
    public function upvote(int $userRef, int $commentRef);

    /**
     * @param int $userRef
     * @param int $commentRef
     * @return mixed
     */
    public function downvote(int $userRef, int $commentRef);
}