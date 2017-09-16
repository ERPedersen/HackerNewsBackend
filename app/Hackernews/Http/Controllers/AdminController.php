<?php

namespace Hackernews\Http\Controllers;

use Hackernews\Http\Handlers\ResponseHandler;
use Slim\Http\Request;
use Slim\Http\Response;

class AdminController
{
    public function admin(Request $request, Response $response)
    {
        return $response->withJson(ResponseHandler::success("Welcome"));
    }
}