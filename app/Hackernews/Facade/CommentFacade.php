<?php
/**
 * Created by PhpStorm.
 * User: Ebbe
 * Date: 9/24/2017
 * Time: 12:38 PM
 */

namespace Hackernews\Facade;

use Hackernews\Access\CommentAccess;

/**
 * Class CommentFacade
 * @package Hackernews\Facade
 */
class CommentFacade implements ICommentFacade
{
    /**
     * @var CommentAccess
     */
    private $access;

    /**
     * CommentFacade constructor.
     * @param CommentAccess|null $access
     */
    function __construct(CommentAccess $access = null)
    {
        $this->access = $access ? $access : new CommentAccess();
    }

    /**
     * @param int $postRef
     * @param int $limit
     * @param int $page
     * @return array
     */
    public function getCommentByPostId(int $postRef, int $limit = 5, int $page = 1)
    {
        return $this->access->getCommentsByPostId($postRef, $limit, $page);
    }

    public function postStandaloneComment(int $userRef, int $postRef, string $content)
    {
        return $this->access->postStandaloneComment($userRef, $postRef, $content);
    }
}