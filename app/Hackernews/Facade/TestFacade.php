<?php

namespace Hackernews\Facade;

use Exception;
use Hackernews\Access\TestAccess;
use Hackernews\Entity\Hanesst;
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

    private $newBody;

    /**
     * @param Request $request
     * @return Request
     */
    public function refactorInitialRequest(Request $request): Request
    {
        $json = $request->getParsedBody();

        $this->newBody = new stdClass();
        $this->newBody->email = $json['username'] . "@test.com";
        $this->newBody->password = $json['pwd_hash'];
        $this->newBody->title = ($json['post_title'] == "") ? "SPAM: This is test spam" : $json['post_title'];
        $this->newBody->hanesst_id = $json['hanesst_id'];
        $this->newBody->content = "TEST STORY: Generated from Helge's script";

        $request = $request->withAttribute("post_type", "story");
        $request = $request->withParsedBody((array)$this->newBody);

        return $request;
    }

    /**
     * @param Request $request
     * @param Response $response
     * @return Request
     * @throws NoUserException
     */
    public function addTokenToHeader(Request $request, Response $response): Request
    {
        $this->newBody->user_id = "911121";

        $request = $request->withAttribute("user_id", "911121");
        $request = $request->withParsedBody((array)$this->newBody);

        return $request;
    }

    /**
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function postRequest(Request $request, Response $response): Response
    {
        $postCtrl = new PostController();
        $response = $postCtrl->createPost($request, $response);

        return $response;
    }

    /**
     * @param Request $request
     * @param Response $response
     * @return mixed
     */
    private function getToken(Request $request, Response $response) {
        $auth = new AuthController();
        return $auth->authenticate($request, $response);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @throws \Exception
     */
    public function persistHanesstId(Request $request, Response $response) {
        $access = new TestAccess();

        $json = $request->getParsedBody();

        $hanesst_id = $json['hanesst_id'];

        try {
            $access->persistHanesstId($hanesst_id);
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * @param Request $request
     * @param Response $response
     * @return \Hackernews\Entity\Hanesst
     * @throws Exception
     */
    public function latestHanesst(Request $request, Response $response): Hanesst {
        $access = new TestAccess();

        try {
            return $access->latestHanesst();
        } catch (Exception $e) {
            throw $e;
        }
    }
}