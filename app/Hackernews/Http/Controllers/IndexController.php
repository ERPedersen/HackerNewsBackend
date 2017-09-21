<?php

namespace Hackernews\Http\Controllers;

use Hackernews\Example;
use Hackernews\Http\Handlers\ResponseHandler;
use Slim\Http\Request;
use Slim\Http\Response;

/**
 * Class IndexController
 *
 * @package Hackernews\Http\Controllers
 */
class IndexController
{
    /**
     * @param \Slim\Http\Request $request
     * @param \Slim\Http\Response $response
     * @return \Slim\Http\Response
     */
    public static function index(Request $request, Response $response)
    {
        return $response->withJson(ResponseHandler::success(getenv('DB_SCHEMA')));
    }
}