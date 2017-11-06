<?php

namespace Hackernews\Http\Controllers;

use Exception;
use Hackernews\Facade\TestFacade;
use Hackernews\Http\Handlers\ResponseHandler;
use Hackernews\Logging\ApiLogger;
use Hackernews\Logging\ExceptionLogger;
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
    public function postTest(Request $request, Response $response)
    {
        try {
            ApiLogger::Instance()->logEndpointEvent("info", $request);

            $facade = new TestFacade();
            $request = $facade->refactorInitialRequest($request);
            $request = $facade->addTokenToHeader($request, $response);
        } catch (Exception $e) {
            ExceptionLogger::Instance()->logEndpointException($e, 'error', $request);
            return $response->withStatus($e->getCode())->withJson(ResponseHandler::error($e));
        }

        $facade->persistHanesstId($request, $response);
        $result = $facade->postRequest($request, $response);

        return $response->withJson(ResponseHandler::success($result));

    }

    /**
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function latestHanesst(Request $request, Response $response)
    {
        try {
            ApiLogger::Instance()->logEndpointEvent("info", $request);

            $facade = new TestFacade();
            $hannest = $facade->latestHanesst($request, $response);

            return $response->withHeader("Content-Type", "text/plain")->write($hannest->getHanesstId());
        } catch (Exception $e) {
            ExceptionLogger::Instance()->logEndpointException($e, 'error', $request);
            return $response->withJson(ResponseHandler::error($e));
        }
    }

    /**
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function status(Request $request, Response $response)
    {
        ApiLogger::Instance()->logEndpointEvent("info", $request);

        return $response->withHeader("Content-Type", "text/plain")->write("Alive");
    }

}