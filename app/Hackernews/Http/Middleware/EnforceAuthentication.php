<?php
/**
 * Created by PhpStorm.
 * User: Emil
 * Date: 16-09-2017
 * Time: 13:14
 */

namespace Hackernews\Http\Middleware;

use Hackernews\Services\TokenService;
use Slim\Http\Request;
use Slim\Http\Response;

/**
 * Class EnforceAuthentication
 *
 * @package Hackernews\Http\Middleware
 */
class EnforceAuthentication
{
    /**
     * @param \Slim\Http\Request $request
     * @param \Slim\Http\Response $response
     * @param $next
     * @return \Slim\Http\Response
     */
    function __invoke(Request $request, Response $response, $next)
    {
        if ($request->hasHeader("Authorization")) {

            $header = $request->getHeaderLine("Authorization");
            $header = str_replace("Bearer ", "", $header);

            $tokenService = new TokenService();

            $token = $tokenService->decode($header);

            if ($token && $tokenService->verify($token)) {
                $request = $request->withAttribute("decoded", $token);

                return $next($request, $response);
            }
        }

        return $response->withStatus(401)->withJson('You do not have permission to request this resource');
    }
}