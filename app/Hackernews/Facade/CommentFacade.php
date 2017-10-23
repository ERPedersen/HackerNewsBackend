<?php
/**
 * Created by PhpStorm.
 * User: Ebbe
 * Date: 9/24/2017
 * Time: 12:38 PM
 */

namespace Hackernews\Facade;

use Hackernews\Access\CommentAccess;
use Exception;

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
    public function postComment(int $userRef, int $postRef, string $content, int $commentRef=null)
    {
        $commentId = $this->access->postComment($userRef, $postRef, $content, $commentRef);

        return [
            'comment_id' => $commentId,
            'comment' => $this->access->getCommentById($commentId)
        ];
    }

    public function upvote(int $userRef, int $commentRef)
    {
        try {
            // Choice will either be 1, 0 or -1.
            // If 0 a new upvote has to be created.
            // If 1 there is already an upvote and it has to be removed.
            // If -1 there is a downvote, and it has to be changed to an upvote.
            $choice = $this->access->getVote($userRef, $commentRef);

            if ($choice == 0) {
                $this->access->addUpvote($userRef, $commentRef);
            } else if ($choice == 1) {
                $this->access->removeUpvote($userRef, $commentRef);
            } else {
                $this->access->changeVote($userRef, $commentRef, 1);
            }
            return $this->access->getCommentById($commentRef);
        } catch (Exception $e) {
            throw  $e;
        }
    }

    public function downvote(int $userRef, int $commentRef)
    {
        try {
            // Choice will either be 1, 0 or -1.
            // If 0 a new downvote has to be created.
            // If 1 there is an upvote and it has to be changed to a downvote.
            // If -1 there is already a downvote and it has to be removed.
            $choice = $this->access->getVote($userRef, $commentRef);

            if ($choice == 0) {
                $this->access->addDownvote($userRef, $commentRef);
            } else if ($choice == -1) {
                $this->access->removeDownvote($userRef, $commentRef);
            } else {
                $this->access->changeVote($userRef, $commentRef, -1);
            }
            return $this->access->getCommentById($commentRef);
        } catch (Exception $e) {
            throw $e;
        }
    }

}