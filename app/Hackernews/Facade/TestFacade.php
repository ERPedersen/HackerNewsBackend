<?php

namespace Hackernews\Facade;

use Exception;
use Hackernews\Access\TestAccess;
use Hackernews\Entity\Hanesst;
use Hackernews\Entity\Post;
use Hackernews\Exceptions\NoUserException;
use Hackernews\Http\Controllers\AuthController;
use Hackernews\Http\Controllers\PostController;
use Hackernews\Services\TokenService;
use Slim\Http\Request;
use Slim\Http\Response;
use stdClass;

/**
 * Class TestFacade
 *
 * @package Hackernews\Facade
 */
class TestFacade implements ITestFacade
{

    /**
     * @var TestAccess
     */
    private $access;

    /**
     * TestFacade constructor.
     * @param TestAccess|null $access
     */
    public function __construct(TestAccess $access = null)
    {
        $this->access = !empty($access) ? $access : new TestAccess();
    }

    /**
     * @param $hanesst_id
     */
    public function updateHanesstId($hanesst_id)
    {
        $this->access->updateHanesstId($hanesst_id);
    }

    /**
     * @return int
     */
    public function getHanesstId(): int
    {
        return $this->access->getHanesstId()->getHanesstId();
    }
}