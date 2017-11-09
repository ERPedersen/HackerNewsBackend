<?php

namespace Hackernews\Access;

use Exception;
use Hackernews\Entity\User;
use Hackernews\Entity\Post;
use Hackernews\Exceptions\DuplicatePostException;
use Hackernews\Exceptions\NoPostsException;
use Hackernews\Exceptions\NoUserException;
use Hackernews\Exceptions\PostNotFoundException;
use Hackernews\Exceptions\WrongValueException;
use Hackernews\Services\DB;
use PDOException;

/**
 * Class PostAccess
 *
 * @package Hackernews\Access
 */
class PostAccess implements IPostAccess
{
    /**
     * Tries to insert a post into the database. If the database exits with a duplicate entry
     * constraint failure, we check if it's the slug causing the error. If it is the slug, we
     * generate a unique slug, and try inserting the post again. If it is not the slug, it is
     * the URL, and we simply throw an exception indicating that it's a repost.
     *
     * @param String $title
     * @param String $slug
     * @param String $url
     * @param String $domain
     * @param int $userRef
     * @return string
     * @throws \Hackernews\Exceptions\DuplicatePostException|\PDOException
     */
    public function createPost(String $title, String $slug, String $url, String $domain, int $userRef)
    {
        try {
            $stmt = DB::conn();
            $stmt->prepare("INSERT INTO posts (title, slug, url, domain, user_ref, type) VALUES (:title, :slug, :url, :domain, :user_ref, :type)")->execute([
                "title" => $title,
                "slug" => $slug,
                "url" => $url,
                "domain" => $domain,
                "user_ref" => $userRef,
                "type" => "link"
            ]);

            $lastId = $stmt->lastInsertId();

            $stmt = null;

            return $lastId;
        } catch (PDOException $e) {
            if ($e->errorInfo[1] == 1062) {
                if (strpos($e->errorInfo[2], 'slug')) {
                    return $this->createPost($title, $this->getUniqueSlug($slug), $url, $domain, $userRef);
                } else {
                    throw new DuplicatePostException("This URL has already been posted before. Reposting is not allowed.", 7);
                }
            } else {
                throw $e;
            }
        }
    }

    /**
     * Tries to insert a post into the database. If the database exits with a duplicate entry
     * constraint failure, we check if it's the slug causing the error. If it is the slug, we
     * generate a unique slug, and try inserting the post again. If it is not the slug, it is
     * the URL, and we simply throw an exception indicating that it's a repost.
     *
     * @param String $title
     * @param String $slug
     * @param String $content
     * @param int $userRef
     * @return string
     */
    public function createStory(String $title, String $slug, String $content, int $userRef)
    {
        try {
            $stmt = DB::conn();
            $stmt->prepare("INSERT INTO posts (title, slug, content, user_ref, type) VALUES (:title, :slug, :content, :user_ref, :type)")->execute([
                "title" => $title,
                "slug" => $slug,
                "content" => $content,
                "user_ref" => $userRef,
                "type" => "story"
            ]);

            $lastId = $stmt->lastInsertId();

            $stmt = null;

            return $lastId;

        } catch (PDOException $e) {
            if ($e->errorInfo[1] == 1062 && strpos($e->errorInfo[2], 'slug')) {
                return $this->createStory($title, $this->getUniqueSlug($slug), $content, $userRef);
            } else {
                throw $e;
            }
        }
    }

    /**
     * Fetches a post, and it's author from the database. This should only be called
     * with a guaranteed post id. For fetching a post from the API, search for a post
     * by slug.
     *
     * @param int $id
     * @param int $userRef
     * @return Post|mixed
     * @throws \Exception|\PDOException
     */
    public function getPostById(int $id, int $userRef)
    {
        try {

            $stmt = DB::conn()->prepare("SELECT 
                                     p.id AS post_id, 
                                     p.title AS post_title,
                                     p.content AS post_content,
                                     p.slug AS post_slug, 
                                     p.url AS post_url, 
                                     p.domain AS post_domain, 
                                     p.karma AS post_karma, 
                                     p.spam AS post_spam,
                                     DATE_FORMAT(CONVERT_TZ( p.created_at, @@session.time_zone, '+00:00' ), '%Y-%m-%dT%TZ') AS post_created_at,
                                     u.id AS author_id, 
                                     u.alias AS author_alias, 
                                     u.karma AS author_karma,
                                     v.val AS my_vote
                                     FROM posts AS p
                                     JOIN users u
                                     ON p.user_ref = u.id
                                     LEFT JOIN votes_users_posts v
                                     ON v.post_ref = p.id AND v.user_ref = :userRef
                                     WHERE p.id = :id
                                     ");

            $stmt->execute([
                'id' => $id,
                'userRef' => $userRef
            ]);

            if ($stmt->rowCount() == 0) {
                throw new NoPostsException("No results found", 0);
            }

            $row = $stmt->fetch();

            $stmt = null;

            $author = new User(
                $row['author_id'],
                $row['author_alias'],
                $row['author_karma']
            );

            $post = new Post(
                $row['post_id'],
                $row['post_title'],
                $row['post_slug'],
                $row['post_content'],
                $row['post_url'],
                $row['post_domain'],
                $row['post_karma'],
                $row['post_created_at'],
                $row['author_id'],
                $row['post_spam'],
                $author,
                isset($row['my_vote']) ? $row['my_vote'] : 0
            );

            return $post;
        } catch (PDOException $e) {
            throw new PostNotFoundException("Post was not found.");
        }
    }

    /**
     * @param String $slug
     * @param int $userRef
     * @return Post
     * @throws NoPostsException
     */
    public function getPostBySlug(String $slug, int $userRef)
    {
        try {
            $stmt = DB::conn()->prepare("SELECT 
                                     p.id AS post_id, 
                                     p.title AS post_title,
                                     p.content AS post_content,
                                     p.slug AS post_slug, 
                                     p.url AS post_url, 
                                     p.domain AS post_domain, 
                                     p.karma AS post_karma, 
                                     p.spam AS post_spam,
                                     DATE_FORMAT(CONVERT_TZ( p.created_at, @@session.time_zone, '+00:00' ), '%Y-%m-%dT%TZ') AS post_created_at,
                                     u.id AS author_id, 
                                     u.alias AS author_alias, 
                                     u.karma AS author_karma,
                                     v.val AS my_vote
                                     FROM posts AS p
                                     JOIN users u
                                     ON p.user_ref = u.id
                                     LEFT JOIN votes_users_posts v
                                     ON v.post_ref = p.id AND v.user_ref = :userRef
                                     WHERE  p.slug = :slug");

            $stmt->execute([
                'slug' => $slug,
                'userRef' => $userRef
            ]);

            if ($stmt->rowCount() == 0) {
                throw new NoPostsException("No results found", 0);
            }

            $row = $stmt->fetch();

            $stmt = null;

            $author = new User(
                $row['author_id'],
                $row['author_alias'],
                $row['author_karma']
            );

            $post = new Post(
                $row['post_id'],
                $row['post_title'],
                $row['post_slug'],
                $row['post_content'],
                $row['post_url'],
                $row['post_domain'],
                $row['post_karma'],
                $row['post_created_at'],
                $row['author_id'],
                $row['post_spam'],
                $author,
                isset($row['my_vote']) ? $row['my_vote'] : 0
            );

            return $post;
        } catch (PDOException $e) {
            throw new PostNotFoundException("Post was not found.");
        }
    }

    /**
     * Fetches all posts
     *
     * @param int $limit
     * @param int $page
     * @param int $userRef
     * @return array
     */
    public function getPosts($limit, $page, int $userRef)
    {
        try {
            $limit = $limit + 1;
            $offset = ($limit - 1) * ($page - 1);

            $stmt = DB::conn()->prepare("SELECT 
                                     p.id AS post_id, 
                                     p.title AS post_title, 
                                     p.slug AS post_slug, 
                                     p.content AS post_content, 
                                     p.url AS post_url, 
                                     p.domain AS post_domain, 
                                     p.karma AS post_karma, 
                                     p.spam AS post_spam, 
                                     DATE_FORMAT(CONVERT_TZ( p.created_at, @@session.time_zone, '+00:00' ), '%Y-%m-%dT%TZ') AS post_created_at,
                                     u.id AS author_id, 
                                     u.alias AS author_alias, 
                                     u.karma AS author_karma,
                                     v.val AS my_vote
                                     FROM posts AS p
                                     JOIN users u
                                     ON p.user_ref = u.id
                                     LEFT JOIN votes_users_posts v
                                     ON v.post_ref = p.id AND v.user_ref = :userRef
                                     ORDER BY p.created_at DESC
                                     LIMIT :limit_amount
                                     OFFSET :offset_amount");

            $stmt->execute([
                'limit_amount' => $limit,
                'offset_amount' => $offset,
                'userRef' => $userRef
            ]);

            $results = [];

            while ($row = $stmt->fetch()) {

                $author = new User(
                    $row['author_id'],
                    $row['author_alias'],
                    $row['author_karma']
                );

                $post = new Post(
                    $row['post_id'],
                    $row['post_title'],
                    $row['post_slug'],
                    $row['post_content'],
                    $row['post_url'],
                    $row['post_domain'],
                    $row['post_karma'],
                    $row['post_created_at'],
                    $row['author_id'],
                    $row['post_spam'],
                    $author,
                    isset($row['my_vote']) ? $row['my_vote'] : 0
                );

                array_push($results, $post);
            }

            $stmt = null;

            if (count($results) == $limit) {
                $hasMore = true;
                array_pop($results);
            } else {
                $hasMore = false;
            }

            return [
                'has_more' => $hasMore,
                'posts' => $results
            ];
        } catch (PDOException $e) {
            throw new NoPostsException("No posts were found.");
        }
    }

    /**
     * Searches the database for matching slugs. It then finds the
     * next slug number and appends it to the slug, making it unique.
     *
     * @param $slug
     * @return string
     */
    public function getUniqueSlug(String $slug)
    {
        try {
            $stmt = DB::conn()->prepare("SELECT slug FROM posts WHERE slug LIKE :slug");
            $stmt->execute(['slug' => $slug . '%']);

            $rowCount = $stmt->rowCount();

            // If there are any matching slugs, we start searching
            if ($rowCount > 0) {

                $max = 1;
                $slugs = [];

                // Put all slugs into an array
                while ($row = $stmt->fetch()) {
                    array_push($slugs, $row['slug']);
                }

                $stmt = null;

                // Keep incrementing, until we don't find anything
                while (in_array(($slug . '-' . $max), $slugs)) {
                    $max++;
                }

                $slug .= '-' . $max;
            }

            return $slug;

        } catch (PDOException $e) {
            throw new Exception("Error occured generating unique slug.");
        }
    }

    /**
     * @param int $userRef
     * @param int $postRef
     * @return int
     * @throws Exception
     */
    public function getVote(int $userRef, int $postRef)
    {
        try {
            $stmt = DB::conn()->prepare("SELECT user_ref, post_ref, val FROM votes_users_posts WHERE user_ref = :user_ref AND post_ref = :post_ref");
            $stmt->execute([
                "user_ref" => $userRef,
                "post_ref" => $postRef
            ]);

            // There is no previous upvote or downvote from the user so one has to be made.
            if ($stmt->rowCount() == 0) {
                return 0;
            }

            $row = $stmt->fetch();
            $val = $row['val'];

            $stmt = null;

            // If val is one, there is already an upvote and therefore it needs to be removed.
            // If val is -1 a downvote exists and needs to be changed to an upvote.
            if ($val == 1 || $val == -1) {
                return ($val);
            } else {
                // Should never happen.
                throw new WrongValueException("Value must be 1 or -1");
            }
        } catch (PDOException $e) {
            throw new PostNotFoundException("Post was not found.");
        }

    }

    /**
     * @param int $userRef
     * @param int $postRef
     * @return string
     * @throws NoPostsException
     * @throws NoUserException
     */
    public function addUpvote(int $userRef, int $postRef)
    {
        try {
            $stmt = DB::conn();

            // Inserts a new upvote into the junction table in the database.
            $stmt->prepare("INSERT INTO votes_users_posts (user_ref, post_ref, val) VALUES (:user_ref, :post_ref, :val)")->execute([
                "user_ref" => $userRef,
                "post_ref" => $postRef,
                "val" => 1
            ]);

            $stmt = null;

            return "upvote added";
        } catch (PDOException $e) {
            if ($e->errorInfo[1] == 1452 && strpos($e->errorInfo[2], 'user_ref')) {
                throw new NoUserException("The User doesn't exists!");
            } else if ($e->errorInfo[1] == 1452 && strpos($e->errorInfo[2], 'post_ref')) {
                throw new NoPostsException("The Post doesn't exists!");
            } else {
                throw $e;
            }
        }
    }

    /**
     * @param int $userRef
     * @param int $postRef
     * @return string
     * @throws NoPostsException
     * @throws NoUserException
     */
    public function removeUpvote(int $userRef, int $postRef)
    {
        try {
            $stmt = DB::conn();

            // Removes an upvote from the junction table in the database.
            $stmt->prepare("DELETE FROM votes_users_posts WHERE user_ref = :user_ref AND post_ref = :post_ref")->execute([
                "user_ref" => $userRef,
                "post_ref" => $postRef
            ]);

            $stmt = null;

            return "upvote removed!";
        } catch (PDOException $e) {
            if ($e->errorInfo[1] == 1452 && strpos($e->errorInfo[2], 'user_ref')) {
                throw new NoUserException("The User doesn't exists!");
            } else if ($e->errorInfo[1] == 1452 && strpos($e->errorInfo[2], 'post_ref')) {
                throw new NoPostsException("The Post doesn't exists!");
            } else {
                throw $e;
            }
        }
    }

    /**
     * @param int $userRef
     * @param int $postRef
     * @return string
     * @throws NoPostsException
     * @throws NoUserException
     */
    public function addDownvote(int $userRef, int $postRef)
    {
        try {
            $stmt = DB::conn();

            // Inserts a new upvote into the junction table in the database.
            $stmt->prepare("INSERT INTO votes_users_posts (user_ref, post_ref, val) VALUES (:user_ref, :post_ref, :val)")->execute([
                "user_ref" => $userRef,
                "post_ref" => $postRef,
                "val" => -1
            ]);

            $stmt = null;

            return "downvote added";
        } catch (PDOException $e) {
            if ($e->errorInfo[1] == 1452 && strpos($e->errorInfo[2], 'user_ref')) {
                throw new NoUserException("The User doesn't exists!");
            } else if ($e->errorInfo[1] == 1452 && strpos($e->errorInfo[2], 'post_ref')) {
                throw new NoPostsException("The Post doesn't exists!");
            } else {
                throw $e;
            }
        }
    }

    /**
     * @param int $userRef
     * @param int $postRef
     * @return string
     * @throws NoPostsException
     * @throws NoUserException
     */
    public function removeDownvote(int $userRef, int $postRef)
    {
        try {
            $stmt = DB::conn();

            // Removes an downvote from the junction table in the database.
            $stmt->prepare("DELETE FROM votes_users_posts WHERE user_ref = :user_ref AND post_ref = :post_ref")->execute([
                "user_ref" => $userRef,
                "post_ref" => $postRef
            ]);

            $stmt = null;

            return "downvote removed!";
        } catch (PDOException $e) {
            if ($e->errorInfo[1] == 1452 && strpos($e->errorInfo[2], 'user_ref')) {
                throw new NoUserException("The User doesn't exists!");
            } else if ($e->errorInfo[1] == 1452 && strpos($e->errorInfo[2], 'post_ref')) {
                throw new NoPostsException("The Post doesn't exists!");
            } else {
                throw $e;
            }
        }
    }

    /**
     * @param int $userRef
     * @param int $postRef
     * @param int $val
     * @return string
     * @throws NoPostsException
     * @throws NoUserException
     */
    public function changeVote(int $userRef, int $postRef, int $val)
    {
        try {
            $stmt = DB::conn();

            // Updates a downvote into an upvote.
            $stmt->prepare("UPDATE votes_users_posts SET val = :val WHERE user_ref = :user_ref AND post_ref = :post_ref")->execute([
                "val" => $val,
                "user_ref" => $userRef,
                "post_ref" => $postRef
            ]);

            $stmt = null;

            return ($val == 1) ? "downvote changed to upvote" : "upvote changed to downvote";
        } catch (PDOException $e) {
            if ($e->errorInfo[1] == 1452 && strpos($e->errorInfo[2], 'user_ref')) {
                throw new NoUserException("The User doesn't exists!");
            } else if ($e->errorInfo[1] == 1452 && strpos($e->errorInfo[2], 'post_ref')) {
                throw new NoPostsException("The Post doesn't exists!");
            } else {
                throw $e;
            }
        }
    }
}