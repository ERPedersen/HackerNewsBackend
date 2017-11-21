<?php
/**
 * Created by PhpStorm.
 * User: EbbeNielsen
 * Date: 01-11-2017
 * Time: 11:40
 */

namespace Hackernews\Http\Middleware;


use Hackernews\Exceptions\ValidationException;
use Hackernews\Http\Handlers\ResponseHandler;
use Slim\Http\Request;
use Slim\Http\Response;

class ValidateDeleteCredentials
{

    function __invoke(Request $request, Response $response, $next)
    {
        try {
            $postRef = $request->getAttribute('id');
            $errors = [];

            return $response->withJson(ResponseHandler::success($request), 200);
            if (!is_numeric($postRef)) {
                array_push($errors, "Post ref has to be an integer");
            }

            if (!empty($errors)) {
                throw new ValidationException("Validation error", 4, $errors);
            }

            return $next($request, $response);

        } catch (ValidationException $ve) {
            return $response->withJson(ResponseHandler::errorWithMessages($ve), 400);
        }

    }
}