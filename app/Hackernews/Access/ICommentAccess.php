<?php
/**
 * Created by PhpStorm.
 * User: Ebbe
 * Date: 9/24/2017
 * Time: 12:28 PM
 */

namespace Hackernews\Access;

/**
 * Interface ICommentAccess
 * @package Hackernews\Access
 */
interface ICommentAccess
{
    /**
     * @param int $postRef
     * @param int $limit
     * @param int $page
     * @param int $userRef
     * @return mixed
     */
    public function getCommentsByPostId(int $postRef, int $limit, int $page, int $userRef);

    /**
     * @param int $commentId
     * @param int $userRef
     * @return mixed
     */
    public function getCommentById(int $commentId, int $userRef);

    /**
     * @param int $userRef
     * @param int $commentRef
     * @return mixed
     */
    public function getVote(int $userRef,int $commentRef);

    /**
     * @param int $userRef
     * @param int $commentRef
     * @return mixed
     */
    public function addUpvote(int $userRef,int $commentRef);

    /**
     * @param int $userRef
     * @param int $commentRef
     * @return mixed
     */
    public function addDownvote(int $userRef,int $commentRef);

    /**
     * @param int $userRef
     * @param int $commentRef
     * @return mixed
     */
    public function removeUpVote(int $userRef,int $commentRef);

    /**
     * @param int $userRef
     * @param int $commentRef
     * @return mixed
     */
    public function removeDownvote(int $userRef,int $commentRef);

    /**
     * @param int $userRef
     * @param int $commentRef
     * @param int $value
     * @return mixed
     */
    public function changeVote(int $userRef,int $commentRef, int $value);


}