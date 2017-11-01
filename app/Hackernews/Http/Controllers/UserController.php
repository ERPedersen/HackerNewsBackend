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

    public function updateUserData(Request $request, Response $response) {

        $json = $request->getParsedBody();

        $id = $request->getAttribute('user_id');
        $email = ($json['email'] == null || $json['email'] == "") ? "" : $json['email'];
        $alias = ($json['alias'] == null || $json['alias'] == "") ? "" : $json['alias'];


        try {
            $userFacade = new UserFacade();
            $user = $userFacade->updateUser($id, $email, $alias);
            return $response->withJson(ResponseHandler::success($user));
        } catch (Exception $e) {
            return $response->withStatus(500)->withJson(ResponseHandler::error($e));
        }
    }
}