<?php

namespace Hackernews\Facade;

use Exception;
use Hackernews\Access\PostAccess;
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

        $slug = UrlService::getSlug($title);
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
}