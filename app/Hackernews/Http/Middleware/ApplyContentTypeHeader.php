<?php

namespace Hackernews\Http\Middleware;


use Exception;
use Hackernews\Http\Handlers\ResponseHandler;
use Slim\Http\Request;
use Slim\Http\Response;

class ApplyContentTypeHeader
{
    function __invoke(Request $request, Response $response, $next)
    {
        try {
            $request = $request->withHeader('CONTENT_TYPE', 'application/json');
            return $next($request, $response);
        } catch (Exception $e) {
            return $response->withStatus(500)->withJson(ResponseHandler::error('Unable to set content type.'));
        }
    }
}