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

    /**
     * @param int $userRef
     * @param int $postRef
     * @param string $content
     * @return array
     */
    public function postStandaloneComment(int $userRef, int $postRef, string $content)
    {
        $commentId = $this->access->postStandaloneComment($userRef, $postRef, $content);

        return [
            'comment_id' => $commentId,
            'comment' => $this->access->getCommentById($commentId)
        ];
    }

    /**
     * @param int $userRef
     * @param int $postRef
     * @param int $commentRef
     * @param string $content
     * @return array
     */
    public function postCommentWithReference(int $userRef, int $postRef, int $commentRef, string $content)
    {
        return $this->access->postCommentWithReference($userRef, $postRef, $commentRef, $content);
    }
}