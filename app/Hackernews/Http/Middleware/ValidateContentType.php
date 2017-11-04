<?php
/**
 * Created by PhpStorm.
 * User: denni
 * Date: 11/4/2017
 * Time: 10:54 AM
 */

namespace Hackernews\Http\Middleware;


use Exception;
use Hackernews\Exceptions\InvalidContentTypeException;
use Hackernews\Http\Handlers\ResponseHandler;
use Slim\Http\Request;
use Slim\Http\Response;

class ValidateContentType
{

    function __invoke(Request $request, Response $response, $next)
    {
        try {
            $headers = $request->getHeaders();


            if(!isset($headers['CONTENT_TYPE']) || strtolower($headers['CONTENT_TYPE'][0]) != "application/json") {
                throw new InvalidContentTypeException("The content type is invalid. Should be Application/Json.", 415);
            }

            return $next($request, $response);

        } catch (Exception $e) {
            return $response->withStatus(415)->withJson(ResponseHandler::error($e));
        }
    }

}