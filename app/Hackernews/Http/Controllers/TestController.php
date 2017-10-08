<?php

namespace Hackernews\Http\Controllers;

use Hackernews\Facade\TestFacade;
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

        $request = $facade->refactorInitialRequest($request);
        $request = $facade->addTokenToHeader($request, $response);

        return $facade->postRequest($request, $response);
    }

}