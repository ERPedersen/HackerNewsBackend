<?php

namespace Hackernews\Http\Controllers;

use Exception;
use Hackernews\Facade\UserFacade;
use Hackernews\Http\Handlers\ResponseHandler;
use Slim\Http\Request;
use Slim\Http\Response;

/**
 * Class UserController
 *
 * @package Hackernews\Http\Controllers
 */
class UserController
{
    public function getUserData(Request $request, Response $response) {
        $id = $request->getAttribute('user_id');

        try {
            $userFacade = new UserFacade();
            $user = $userFacade->getUserData($id);

            return $response->withJson(ResponseHandler::success($user));
        } catch (Exception $e) {
            return $response->withStatus(500)->withJson(ResponseHandler::error($e));
        }
    }
}