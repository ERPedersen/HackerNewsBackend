<?php

namespace Hackernews\Http\Controllers;

use Exception;
use Hackernews\Exceptions\DuplicateUserException;
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
     * @middleware \Hackernews\Http\Middleware\ValidateLoginCredentials
     * @middleware \Hackernews\Http\Middleware\AllowCrossOrigin
     */
    public function authenticate(Request $request, Response $response)
    {
        $json = $request->getParsedBody();

        $email = $json['email'];
        $password = $json['password'];

        try {
            $userFacade = new UserFacade();
            $token = $userFacade->verifyUser($email, $password);

            return $response->withJson(ResponseHandler::success($token));
        } catch (Exception $e) {
            return $response->withStatus(401)->withJson(ResponseHandler::error($e));
        }
    }

    /**
     * @param \Slim\Http\Request $request
     * @param \Slim\Http\Response $response
     * @return \Slim\Http\Response
     * @middleware \Hackernews\Http\Middleware\ValidateSignUpCredentials
     * @middleware \Hackernews\Http\Middleware\AllowCrossOrigin
     */
    public function signUp(Request $request, Response $response)
    {
        $json = $request->getParsedBody();

        $email = $json['email'];
        $password = $json['password'];
        $alias = $json['alias'];
        $test = "Checking for merge conflicts";

        try {
            $userFacade = new UserFacade();
            $token = $userFacade->createUser($email, $password, $alias);

            return $response->withJson(ResponseHandler::success($token));
        } catch (DuplicateUserException $e) {
            return $response->withStatus(409)->withJson(ResponseHandler::error($e));
        } catch (Exception $e) {
            return $response->withStatus(500)->withJson(ResponseHandler::error($e));
        }
    }
}