<?php

namespace Hackernews\Http\Controllers;

use Hackernews\Facade\UserFacade;
use Hackernews\Http\Handlers\ResponseHandler;
use Slim\Http\Request;
use Slim\Http\Response;

/**
 * Class SignupController
 *
 * @package Hackernews\Http\Controllers
 */
class SignUpController
{
    public function signUp(Request $request, Response $response)
    {
        $json = $request->getParsedBody();

        $email = $json['email'];
        $password = $json['password'];
        $alias = $json['alias'];

        try {
            $userFacade = new UserFacade();
            $userFacade->createUser($email, $password, $alias);

            return $response->withJson("User created");
        } catch (Exception $e) {
            return $response->withStatus(401)->withJson(ResponseHandler::error($e->getCode(), $e->getMessage(), null));
        }

    }
}