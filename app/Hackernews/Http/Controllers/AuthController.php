<?php

namespace Hackernews\Http\Controllers;

use Exception;
use Hackernews\Facade\UserFacade;
use Hackernews\Http\Handlers\ResponseHandler;
use Hackernews\Services\TokenService;
use Slim\Http\Request;
use Slim\Http\Response;

/**
 * Class AuthController
 *
 * @package Hackernews\Http\Controllers
 */
class AuthController
{
    /**
     * @param \Slim\Http\Request $request
     * @param \Slim\Http\Response $response
     * @return \Slim\Http\Response
     */
    public function authenticate(Request $request, Response $response)
    {
        $json = $request->getParsedBody();

        $email = $json['email'];
        $password = $json['password'];

        try {
            $userFacade = new UserFacade();
            $user = $userFacade->verifyUser($email, $password);

            $tokenService = new TokenService();

            $token = $tokenService->sign([
                "email" => $user->getEmail(),
                "karma" => $user->getKarma(),
            ]);

            return $response->withJson(ResponseHandler::success($token));
        } catch (Exception $e) {
            return $response->withStatus(401)->withJson(ResponseHandler::error($e->getCode(), $e->getMessage(), null));
        }
    }
}