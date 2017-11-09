<?php
/**
 * Created by PhpStorm.
 * User: Ebbe
 * Date: 9/24/2017
 * Time: 12:27 PM
 */

namespace Hackernews\Access;

use Exception;
use Hackernews\Entity\Comment;
use Hackernews\Entity\User;
use Hackernews\Exceptions\NoCommentsException;
use Hackernews\Exceptions\NoUserException;
use Hackernews\Exceptions\PostNotFoundException;
use Hackernews\Exceptions\ReferenceNotFoundException;
use Hackernews\Exceptions\WrongValueException;
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
     * @param int $limit
     * @param int $page
     * @param int $userRef
     * @return array
     */
    public function getCommentsByPostId(int $postRef, int $limit, int $page, int $userRef)
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
            u.alias       AS user_alias,  
            v.val         AS my_vote  
            FROM comments c
            JOIN users u 
            ON c.user_ref = u.id
            LEFT JOIN votes_users_comments v
            ON v.user_ref = :userRef AND v.comment_ref = c.id
            WHERE post_ref = :post_ref 
            ORDER BY c.created_at DESC
            LIMIT :limit_amount
            OFFSET :offset_amount
        ");

            $stmt->execute([
                'post_ref' => $postRef,
                'limit_amount' => $limit,
                'offset_amount' => $offset,
                'userRef' => $userRef
            ]);

            $results = [];

            while ($row = $stmt->fetch()) {
                $user = new User(
                    $row['user_id'],
                    $row['user_alias'],
                    $row['user_karma']
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
                    $user,
                    isset($row['my_vote']) ? $row['my_vote'] : 0
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

    public function postComment(int $userRef, int $postRef, string $content, int $commentRef = null)
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
                throw new ReferenceNotFoundException("Reference was not found to either post or user.", 8);
            } else {
                throw new Exception("Unhandled exception thrown.", 500);
            }
        }
    }

    /**
     * @param int $commentId
     * @param int $userRef
     * @return Comment|null
     * @throws PostNotFoundException
     */
    public function getCommentById(int $commentId, int $userRef)
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
                u.alias       AS user_alias,
                v.val         AS my_vote  
                FROM comments c
                JOIN users u 
                ON c.user_ref = u.id
                LEFT JOIN votes_users_comments v
                ON v.comment_ref = c.id AND v.user_ref = :userRef
                WHERE c.id = :comment_id
            ");

            $stmt->execute([
                'comment_id' => $commentId,
                'userRef' => $userRef
            ]);

            $comment = null;

            while ($row = $stmt->fetch()) {
                $user = new User(
                    $row['user_id'],
                    $row['user_alias'],
                    $row['user_karma']
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
                    $user,
                    isset($row['my_vote']) ? $row['my_vote'] : 0
                );

            }

            return $comment;

        } catch (PDOException $e) {
            throw new PostNotFoundException("No comment found by this ID.", 404);
        }
    }

    /**
     * @param int $userRef
     * @param int $commentRef
     * @return int
     * @throws WrongValueException
     */
    public function getVote(int $userRef, int $commentRef)
    {
        try {


            $stmt = DB::conn()->prepare("SELECT user_ref, comment_ref, val FROM votes_users_comments WHERE user_ref = :user_ref AND comment_ref = :post_ref");
            $stmt->execute([
                "user_ref" => $userRef,
                "post_ref" => $commentRef
            ]);

            // There is no previous upvote or downvote from the user so one has to be made.
            if ($stmt->rowCount() == 0) {
                return 0;
            }

            $row = $stmt->fetch();
            $val = $row['val'];

            // If val is one, there is already an upvote and therefore it needs to be removed.
            // If val is -1 a downvote exists and needs to be changed to an upvote.
            if ($val == 1 || $val == -1) {
                return ($val);
            } else {
                // Should never happen.
                throw new WrongValueException("Value must be 1 or -1");
            }
        } catch (PDOException $e) {
            if ($e->errorInfo[1] == 1452 && strpos($e->errorInfo[2], 'user_ref')) {
                throw new NoUserException("The User doesn't exists!");
            } else if ($e->errorInfo[1] == 1452 && strpos($e->errorInfo[2], 'comment_ref')) {
                throw new NoCommentsException("The Comment doesn't exists!");
            } else {
                throw $e;
            }
        }
    }

    /**
     * @param int $userRef
     * @param int $commentRef
     * @return string
     * @throws NoCommentsException
     * @throws NoUserException
     */
    public function addUpvote(int $userRef, int $commentRef)
    {
        try {
            $stmt = DB::conn();

            // Inserts a new upvote into the junction table in the database.
            $stmt->prepare("INSERT INTO votes_users_comments (user_ref, comment_ref, val) VALUES (:user_ref, :comment_ref, :val)")->execute([
                "user_ref" => $userRef,
                "comment_ref" => $commentRef,
                "val" => 1
            ]);

            return "upvote added";
        } catch (PDOException $e) {
            if ($e->errorInfo[1] == 1452 && strpos($e->errorInfo[2], 'user_ref')) {
                throw new NoUserException("The User doesn't exists!");
            } else if ($e->errorInfo[1] == 1452 && strpos($e->errorInfo[2], 'comment_ref')) {
                throw new NoCommentsException("The Comment doesn't exists!");
            } else {
                throw $e;
            }
        }
    }

    /**
     * @param int $userRef
     * @param int $commentRef
     * @return string
     * @throws NoCommentsException
     * @throws NoUserException
     */
    public function addDownvote(int $userRef, int $commentRef)
    {
        try {
            $stmt = DB::conn();

            // Inserts a new upvote into the junction table in the database.
            $stmt->prepare("INSERT INTO votes_users_comments (user_ref, comment_ref, val) VALUES (:user_ref, :comment_ref, :val)")->execute([
                "user_ref" => $userRef,
                "comment_ref" => $commentRef,
                "val" => -1
            ]);

            return "downvote added";
        } catch (PDOException $e) {
            if ($e->errorInfo[1] == 1452 && strpos($e->errorInfo[2], 'user_ref')) {
                throw new NoUserException("The User doesn't exists!");
            } else if ($e->errorInfo[1] == 1452 && strpos($e->errorInfo[2], 'comment_ref')) {
                throw new NoCommentsException("The Comment doesn't exists!");
            } else {
                throw $e;
            }
        }
    }

    /**
     * @param int $userRef
     * @param int $commentRef
     * @return string
     * @throws NoCommentsException
     * @throws NoUserException
     */
    public function removeUpVote(int $userRef, int $commentRef)
    {
        try {
            $stmt = DB::conn();

            // Removes an upvote from the junction table in the database.
            $stmt->prepare("DELETE FROM votes_users_comments WHERE user_ref = :user_ref AND comment_ref = :comment_ref")->execute([
                "user_ref" => $userRef,
                "comment_ref" => $commentRef
            ]);

            return "upvote removed!";
        } catch (PDOException $e) {
            if ($e->errorInfo[1] == 1452 && strpos($e->errorInfo[2], 'user_ref')) {
                throw new NoUserException("The User doesn't exists!");
            } else if ($e->errorInfo[1] == 1452 && strpos($e->errorInfo[2], 'comment_ref')) {
                throw new NoCommentsException("The Comment doesn't exists!");
            } else {
                throw $e;
            }
        }
    }

    /**
     * @param int $userRef
     * @param int $commentRef
     * @return string
     * @throws NoCommentsException
     * @throws NoUserException
     */
    public function removeDownvote(int $userRef, int $commentRef)
    {
        try {
            $stmt = DB::conn();

            // Removes an downvote from the junction table in the database.
            $stmt->prepare("DELETE FROM votes_users_comments WHERE user_ref = :user_ref AND comment_ref = :comment_ref")->execute([
                "user_ref" => $userRef,
                "comment_ref" => $commentRef
            ]);

            return "downvote removed!";
        } catch (PDOException $e) {
            if ($e->errorInfo[1] == 1452 && strpos($e->errorInfo[2], 'user_ref')) {
                throw new NoUserException("The User doesn't exists!");
            } else if ($e->errorInfo[1] == 1452 && strpos($e->errorInfo[2], 'comment_ref')) {
                throw new NoCommentsException("The Comment doesn't exists!");
            } else {
                throw $e;
            }
        }
    }

    /**
     * @param int $userRef
     * @param int $commentRef
     * @param int $value
     * @return string
     * @throws NoCommentsException
     * @throws NoUserException
     */
    public function changeVote(int $userRef, int $commentRef, int $value)
    {
        try {
            $stmt = DB::conn();

            // Updates a downvote into an upvote.
            $stmt->prepare("UPDATE votes_users_comments SET val = :val WHERE user_ref = :user_ref AND comment_ref = :comment_ref")->execute([
                "val" => $value,
                "user_ref" => $userRef,
                "comment_ref" => $commentRef
            ]);

            return ($value == 1) ? "downvote changed to upvote" : "upvote changed to downvote";
        } catch (PDOException $e) {
            if ($e->errorInfo[1] == 1452 && strpos($e->errorInfo[2], 'user_ref')) {
                throw new NoUserException("The User doesn't exists!");
            } else if ($e->errorInfo[1] == 1452 && strpos($e->errorInfo[2], 'comment_ref')) {
                throw new NoCommentsException("The Comment doesn't exists!");
            } else {
                throw $e;
            }
        }
    }
}