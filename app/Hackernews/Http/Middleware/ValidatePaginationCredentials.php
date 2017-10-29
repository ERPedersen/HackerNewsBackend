<?php
/**
 * Created by PhpStorm.
 * User: Emil
 * Date: 24-09-2017
 * Time: 03:00
 */

namespace Hackernews\Http\Middleware;

use Hackernews\Exceptions\PaginationException;
use Hackernews\Http\Handlers\ResponseHandler;
use Slim\Http\Request;
use Slim\Http\Response;

/**
 * Class ValidatePaginationCredentials
 *
 * @package Hackernews\Http\Middleware
 */
class ValidatePaginationCredentials
{
    /**
     * @param \Slim\Http\Request $request
     * @param \Slim\Http\Response $response
     * @param $next
     * @return \Slim\Http\Response
     */
    function __invoke(Request $request, Response $response, $next)
    {
        $errors = [];

        if (null === $request->getParam('limit') && null !== $request->getParam('page')) {
            array_push($errors, "Parameter 'page' can only be used in conjunction with the 'limit' parameter.");
        }

        if (null !== $request->getParam('limit') && $request->getParam('limit') < 1) {
            array_push($errors, "Parameter 'limit' must be at least 1");
        }

        if (null === $request->getParam('page') && null !== $request->getParam('limit')) {
            array_push($errors, "Parameter 'limit' can only be used in conjunction with the 'page' parameter.");
        }

        if (null !== $request->getParam('page') && $request->getParam('page') < 1) {
            array_push($errors, "Parameter 'page' must be at least 1");
        }

        if (! empty($errors)) {
            return $response->withJson(ResponseHandler::errorWithMessages(new PaginationException("Invalid pagination parameters", 4, $errors)), 400);
        }

        return $next($request, $response);
    }
}