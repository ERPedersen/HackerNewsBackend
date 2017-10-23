<?php

namespace Hackernews\Http\Controllers;

use Exception;
use Hackernews\Facade\TestFacade;
use Hackernews\Http\Handlers\ResponseHandler;
use Slim\Http\Request;
use Slim\Http\Response;

/**
 * Class TestController
 *
 * @package Hackernews\Http\Controllers
 */
class TestController
{

    /**
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function postTest(Request $request, Response $response) {

        $facade = new TestFacade();

        try {
            $request = $facade->refactorInitialRequest($request);
            $request = $facade->addTokenToHeader($request, $response);
            $facade->persistHanesstId($request, $response);

            $result = $facade->postRequest($request, $response);

            return $response->withJson(ResponseHandler::success($result));

        } catch (Exception $e) {
            return $response->withJson(ResponseHandler::error($e));
        }

    }

}