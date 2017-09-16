<?php
/**
 * Created by PhpStorm.
 * User: Emil
 * Date: 16-09-2017
 * Time: 22:46
 */

namespace Hackernews\Http\Middleware;

use Slim\Http\Request;
use Slim\Http\Response;

/**
 * Class AllowCrossOrigin
 *
 * @package Hackernews\Http\Middleware
 */
class AllowCrossOrigin
{
    /**
     * @param \Slim\Http\Request $request
     * @param \Slim\Http\Response $response
     * @param $next
     * @return mixed
     */
    function __invoke(Request $request, Response $response, $next)
    {
        $response = $response->withHeader('Access-Control-Allow-Origin', '*');

        return $next($request, $response);
    }
}