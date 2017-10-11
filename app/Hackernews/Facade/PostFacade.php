<?php

namespace Hackernews\Facade;

use Exception;
use Hackernews\Access\PostAccess;
use Hackernews\Entity\Post;
use Hackernews\Services\UrlService;

/**
 * Class PostFacade
 *
 * @package Hackernews\Facade
 */
class PostFacade implements IPostFacade
{
    /**
     * @var \Hackernews\Access\PostAccess|null
     */
    private $access;

    /**
     * PostFacade constructor.
     *
     * @param \Hackernews\Access\PostAccess|null $access
     */
    function __construct(PostAccess $access = null)
    {
        $this->access = $access ? $access : new PostAccess();
    }

    /**
     * @param $title
     * @param $url
     * @param $userRef
     * @return mixed
     * @throws \Exception
     */
    public function createPost(String $title, String $url, int $userRef)
    {
        $slug = mb_strtolower(urldecode(UrlService::getSlug($title)), 'UTF-8');
        $domain = UrlService::getDomain($url);

        try {
            $inserted_id = $this->access->createPost($title, $slug, $url, $domain, $userRef);
        } catch (Exception $e) {
            throw $e;
        }

        try {
            return $this->access->getPostById($inserted_id);
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $title
     * @param $url
     * @param $userRef
     * @return mixed
     * @throws \Exception
     */
    public function createStory(String $title, String $content, int $userRef)
    {
        $slug = mb_strtolower(urldecode(UrlService::getSlug($title)), 'UTF-8');

        try {
            $inserted_id = $this->access->createStory($title, $slug, $content, $userRef);
        } catch (Exception $e) {
            throw $e;
        }

        try {
            return $this->access->getPostById($inserted_id);
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * @return mixed
     */
    public function getPosts($limit = 5, $page = 1)
    {
        return $this->access->getPosts($limit, $page);
    }

    /**
     * @param $slug
     * @return Post
     */
    public function getPostBySlug($slug)
    {
        $slug = mb_strtolower($slug, 'UTF-8');

        return $this->access->getPostBySlug($slug);
    }

    /**
     * @param int $userRef
     * @param int $postRef
     * @return Post|mixed
     * @throws Exception
     */
    public function upvote(int $userRef, int $postRef)
    {
        try {
            // Choice will either be 1, 0 or -1.
            // If 0 a new upvote has to be created.
            // If 1 there is already an upvote and it has to be removed.
            // If -1 there is a downvote, and it has to be changed to an upvote.
            $choice = $this->access->getVote($userRef, $postRef);

            if ($choice == 0) {
                $this->access->addUpvote($userRef, $postRef);
            } else if ($choice == 1) {
                $this->access->removeUpvote($userRef, $postRef);
            } else {
                $this->access->changeVote($userRef, $postRef, 1);
            }
            return $this->access->getPostById($postRef);
        } catch (Exception $e) {
            throw  $e;
        }
    }

    /**
     * @param int $userRef
     * @param int $postRef
     * @return Post|mixed
     * @throws Exception
     */
    public function downvote(int $userRef, int $postRef)
    {
        try {
            // Choice will either be 1, 0 or -1.
            // If 0 a new downvote has to be created.
            // If 1 there is an upvote and it has to be changed to a downvote.
            // If -1 there is already a downvote and it has to be removed.
            $choice = $this->access->getVote($userRef, $postRef);

            if ($choice == 0) {
                $this->access->addDownvote($userRef, $postRef);
            } else if ($choice == -1) {
                $this->access->removeDownvote($userRef, $postRef);
            } else {
                $this->access->changeVote($userRef, $postRef, -1);
            }
            return $this->access->getPostById($postRef);
        } catch (Exception $e) {
            throw $e;
        }
    }
}