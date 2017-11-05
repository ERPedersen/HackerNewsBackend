<?php

namespace Hackernews\Http\Controllers;

use Exception;
use Hackernews\Exceptions\DuplicateUserException;
use Hackernews\Facade\UserFacade;
use Hackernews\Http\Handlers\ResponseHandler;
use Hackernews\Logging\ApiLogger;
use Hackernews\Logging\ExceptionLogger;
use Hackernews\Logging\UserLogger;
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
        try {
            ApiLogger::Instance()->logEndpointEvent("info", $request);

            $json = $request->getParsedBody();
            $email = $json['email'];
            $password = $json['password'];
            $userFacade = new UserFacade();
            $token = $userFacade->verifyUser($email, $password);

            UserLogger::Instance()->info("Login", ["email" => $email]);
            return $response->withJson(ResponseHandler::success($token));
        } catch (Exception $e) {
            ExceptionLogger::Instance()->logEndpointException($e, 'error', $request);
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
        try {
            ApiLogger::Instance()->logEndpointEvent("info", $request);

            $json = $request->getParsedBody();
            $email = $json['email'];
            $password = $json['password'];
            $alias = $json['alias'];
            $userFacade = new UserFacade();
            $token = $userFacade->createUser($email, $password, $alias);

            UserLogger::Instance()->info("Sign up", ["email" => $email]);
            return $response->withJson(ResponseHandler::success($token));
        } catch (DuplicateUserException $e) {
            ExceptionLogger::Instance()->logEndpointException($e, 'notice', $request);
            return $response->withStatus(409)->withJson(ResponseHandler::error($e));
        } catch (Exception $e) {
            ExceptionLogger::Instance()->logEndpointException($e, 'error', $request);
            return $response->withStatus(500)->withJson(ResponseHandler::error($e));
        }
    }
}