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
}