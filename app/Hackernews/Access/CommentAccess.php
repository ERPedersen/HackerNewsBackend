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
use Hackernews\Exceptions\ReferenceNotFoundException;
use Hackernews\Services\DB;
use Mockery\Exception;
use PDOException;

/**
 * Class CommentAccess
 * @package Hackernews\Access
 */
class CommentAccess implements ICommentAccess
{
    /**
     * @param int $postRef
     * @param int $limit
     * @param int $page
     * @return array
     */
    public function getCommentsByPostId(int $postRef, int $limit, int $page)
    {
        // Set pagination variables
        $limit = $limit + 1;
        $offset = ($limit - 1) * ($page - 1);

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
            LIMIT :limit_amount
            OFFSET :offset_amount
        ");

            $stmt->execute(['post_ref' => $postRef, 'limit_amount' => $limit, 'offset_amount' => $offset]);

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

            // Check pagination
            if (count($results) == $limit) {
                $hasMore = true;
                array_pop($results);
            } else {
                $hasMore = false;
            }

            return [
                'has_more' => $hasMore,
                'comments' => $results
            ];
        } catch (PDOException $e) {
            throw $e;
        }
    }

    public function postStandaloneComment(int $userRef, int $postRef, string $content)
    {
        try {
            $stmt = DB::conn()->prepare("
            INSERT INTO comments (user_ref, post_ref, content)
            VALUES (:user_ref, :post_ref, :content)
            ");

            $stmt->execute([
                'user_ref' => $userRef,
                'post_ref' => $postRef,
                'content' => $content
            ]);

            return DB::conn()->lastInsertId();


        } catch (PDOException $e) {
            if ($e->errorInfo[0] == 23000) {
                throw new ReferenceNotFoundException("Reference was not found to either post or user.", 8);
            } else {
                throw new Exception("Unhandled exception thrown.",500);
            }
        }
    }

    /**
     * @param int $commentId
     * @return Comment
     */
    public function getCommentById(int $commentId)
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
                WHERE c.id = :comment_id
            ");

            $stmt->execute([
                'comment_id' => $commentId
            ]);

            $comment = null;

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
                    $row['comment_created_at']
                );

            }

            return $comment

        } catch (PDOException £e) {

        }
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
        try {
            $stmt = DB::conn()->prepare("
            INSERT INTO comments (user_ref, post_ref, comment_ref, content)
            VALUES (:user_ref, :post_ref, :comment_ref, :content)
            ");

            $stmt->execute([
                'user_ref' => $userRef,
                'post_ref' => $postRef,
                'comment_ref' => $commentRef,
                'content' => $content
            ]);

            return DB::conn()->lastInsertId();


        } catch (PDOException $e) {
            if ($e->errorInfo[0] == 23000) {
                throw new ReferenceNotFoundException("Reference was not found to post, user or comment.", 8);
            } else {
                throw new Exception("Unhandled exception thrown.",500);
            }
        }
    }
}