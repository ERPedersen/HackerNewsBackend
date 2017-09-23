<?php

namespace Hackernews\Access;

use Hackernews\Entity\User;
use Hackernews\Entity\Post;
use Hackernews\Exceptions\DuplicatePostException;
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
            DB::conn()->prepare("INSERT INTO posts (title, slug, url, domain, user_ref) VALUES (:title, :slug, :url, :domain, :user_ref)")->execute([
                "title" => $title,
                "slug" => $slug,
                "url" => $url,
                "domain" => $domain,
                "user_ref" => $userRef,
            ]);

            return DB::conn()->lastInsertId();
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
     * Fetches a post, and it's author from the database. This should only be called
     * with a guaranteed post id. For fetching a post from the API, search for a post
     * by slug.
     *
     * @param int $id
     * @return array
     * @throws \Exception|\PDOException
     */
    public function getPostById(int $id)
    {

        $stmt = DB::conn()->prepare("SELECT 
                                     p.id AS post_id, 
                                     p.title AS post_title, 
                                     p.slug AS post_slug, 
                                     p.url AS post_url, 
                                     p.domain AS post_domain, 
                                     p.karma AS post_karma, 
                                     p.spam AS post_spam, 
                                     u.id AS author_id, 
                                     u.alias AS author_alias, 
                                     u.karma AS author_karma
                                     FROM posts AS p
                                     RIGHT JOIN users u
                                     ON p.user_ref = u.id
                                     WHERE  p.id = :id");

        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();

        $author = new User($row['author_id'], $row['author_alias'], $row['author_karma']);
        $post = new Post($row['post_id'], $row['post_title'], $row['post_slug'], $row['post_url'], $row['post_domain'], $row['post_karma'], $row['author_id'], $row['post_spam']);

        return ['author' => $author, 'post' => $post];
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

        $stmt = DB::conn()->prepare("SELECT slug FROM posts WHERE slug LIKE :slug");
        $stmt->execute(['slug' => $slug.'%']);

        $rowCount = $stmt->rowCount();

        // If there are any matching slugs, we start searching
        if ($rowCount > 0) {

            $max = 1;
            $slugs = [];

            // Put all slugs into an array
            while ($row = $stmt->fetch()) {
                array_push($slugs, $row['slug']);
            }

            // Keep incrementing, until we don't find anything
            while (in_array(($slug.'-'.$max), $slugs)) {
                $max++;
            }

            $slug .= '-'.$max;
        }

        return $slug;
    }
}