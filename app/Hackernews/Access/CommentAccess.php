<?php
/**
 * Created by PhpStorm.
 * User: Ebbe
 * Date: 9/24/2017
 * Time: 12:27 PM
 */

namespace Hackernews\Access;

use Hackernews\Entity\Comment;
use Hackernews\Entity\User;
use Hackernews\Services\DB;
use PDOException;

/**
 * Class CommentAccess
 * @package Hackernews\Access
 */
class CommentAccess implements ICommentAccess
{
    /**
     * @param int $postRef
     * @return array
     */
    public function getCommentsByPostId(int $postRef)
    {
        try {
            $stmt = DB::conn()->prepare("
            SELECT c.id   AS comment_id, 
            c.user_ref    AS comment_user_ref, 
            c.post_ref    AS comment_post_ref, 
            c.comment_ref AS comment_comment_ref, 
            c.content     AS comment_content, 
            c.karma       AS comment_karma, 
            c.spam        AS comment_spam, 
            c.created_at  AS comment_created_at, 
            u.id          AS user_id, 
            u.karma       AS user_karma, 
            u.alias       AS user_alias  
            FROM comments c
            JOIN users u ON c.user_ref = u.id
            WHERE post_ref = :post_ref
            ORDER BY c.created_at DESC
        ");

            $stmt->execute(['post_ref' => $postRef]);

            $results = [];

            while ($row = $stmt->fetch()) {
                $user = new User(
                    $row['user_id'],
                    $row['user_karma'],
                    $row['user_alias']
                );

                $comment = new Comment(
                    $row['comment_id'],
                    $row['comment_user_ref'],
                    $row['comment_post_ref'],
                    $row['comment_comment_ref'],
                    $row['comment_content'],
                    $row['comment_karma'],
                    $row['comment_spam'],
                    $row['comment_created_at'],
                    $user
                );

                array_push($results, $comment);
            }

            return $results;
        } catch (PDOException $e) {
            throw $e;
        }
    }
}