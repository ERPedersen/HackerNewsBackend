<?php

namespace Hackernews\Facade;

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
        $this->newBody->title = $json['post_title'];

        $request = $request->withParsedBody((array)$this->newBody);

        return $request;
    }

    /**
     * @param Request $request
     * @param Response $response
     * @return Request
     */
    public function addTokenToHeader(Request $request, Response $response): Request
    {
        $responseWithToken = $this->getToken($request, $response);
        $arr = json_decode($responseWithToken->getBody(), true);
        $token = $arr['data'];

        $request = $request->withAddedHeader('Authorization', 'Bearer ' . $token);

        $tokenService = new TokenService();
        $token = $tokenService->decode($token);

        $this->newBody->user_id = $token->getClaim('id');
        $this->newBody->url = "https://www.google.dk/";

        $request = $request->withAttribute("user_id", $token->getClaim('id'));
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
}