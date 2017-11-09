<?php

namespace Hackernews\Http\Controllers;

use Exception;
use Hackernews\Facade\PostFacade;
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

            $testFacade = new TestFacade();
            $postFacade = new PostFacade();

            $postFacade->createStory(
                $request->getAttribute('title'),
                $request->getAttribute('content'),
                $request->getAttribute('user_id')
            );

            $testFacade->updateHanesstId($request->getAttribute('hanesst_id'));

            return $response;

        } catch (Exception $e) {
            ExceptionLogger::Instance()->logEndpointException($e, 'error', $request);
            return $response->withStatus($e->getCode())->withJson(ResponseHandler::error($e));
        }
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

            $testFacade = new TestFacade();

            return $response->withHeader("Content-Type", "text/plain")->write($testFacade->getHanesstId());
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