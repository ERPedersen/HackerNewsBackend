<?php

namespace Hackernews\Controllers;

use Hackernews\Example;
use Slim\Http\Request;
use Slim\Http\Response;

class IndexController
{
    public static function index(Request $request, Response $response)
    {
        $example = new Example();

        return $response->withJson($example->hello());
    }
}