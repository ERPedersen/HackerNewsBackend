<?php
/**
 * Created by PhpStorm.
 * User: denni
 * Date: 11/4/2017
 * Time: 8:19 PM
 */

namespace Hackernews\Http\Middleware;


use Hackernews\Exceptions\WrongValueException;
use Hackernews\Http\Handlers\ResponseHandler;
use Slim\Http\Request;
use Slim\Http\Response;

class ValidateTestPostValues
{

    function __invoke(Request $request, Response $response, $next)
    {
        $json = $request->getParsedBody();

        if(
            isset($json['username']) &&
            isset($json['post_type']) &&
            isset($json['pwd_hash']) &&
            isset($json['post_title']) &&
            isset($json['post_parent']) &&
            isset($json['hanesst_id']) &&
            isset($json['post_text'])
        ) {
            return $next($request, $response);
        } else {
            return $response->withStatus(422)->withJson(ResponseHandler::error(new WrongValueException('Request format is invalid')));
        }
    }

}