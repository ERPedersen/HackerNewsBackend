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
     * @return mixed
     */
    public function getCommentsByPostId(int $postRef);
}