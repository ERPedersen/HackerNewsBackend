<?php

namespace Hackernews\Http\Controllers;

use Hackernews\Http\Handlers\ResponseHandler;
use Slim\Http\Request;
use Slim\Http\Response;

/**
 * Class AdminController
 *
 * @package Hackernews\Http\Controllers
 */
class AdminController
{
    /**
     * @param \Slim\Http\Request $request
     * @param \Slim\Http\Response $response
     * @return \Slim\Http\Response
     */
    public function admin(Request $request, Response $response)
    {

        $alias = $request->getAttribute('user_alias');

        return $response->withJson(ResponseHandler::success("Welcome ".$alias));
    }
}