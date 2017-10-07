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
}