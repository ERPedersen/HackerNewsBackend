<?php

namespace Hackernews\Http\Controllers;

use Exception;
use Hackernews\Facade\UserFacade;
use Hackernews\Http\Handlers\ResponseHandler;
use Hackernews\Logging\ApiLogger;
use Hackernews\Logging\ExceptionLogger;
use PDOException;
use Slim\Http\Request;
use Slim\Http\Response;

/**
 * Class UserController
 *
 * @package Hackernews\Http\Controllers
 */
class UserController
{
    public function getUserData(Request $request, Response $response)
    {
        try {
            ApiLogger::Instance()->logEndpointEvent("info", $request);

            $id = $request->getAttribute('user_id');
            $userFacade = new UserFacade();
            $user = $userFacade->getUserData($id);

            return $response->withJson(ResponseHandler::success($user));
        } catch (PDOException $e) {
            ExceptionLogger::Instance()->logEndpointException($e, 'error', $request);
            return $response->withStatus(500)->withJson(ResponseHandler::dbError());
        } catch (Exception $e) {
            ExceptionLogger::Instance()->logEndpointException($e, 'error', $request);
            return $response->withStatus(500)->withJson(ResponseHandler::error($e));
        }
    }
}