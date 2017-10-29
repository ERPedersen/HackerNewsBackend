<?php

namespace Hackernews\Http\Middleware;

use Hackernews\Services\TokenService;
use Slim\Http\Request;
use Slim\Http\Response;

/**
 * Class CheckIsLoggedIn
 *
 * @package Hackernews\Http\Middleware
 */
class CheckIsLoggedIn
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

                $request = $request->withAttributes([
                    "user_id" => $token->getClaim('id'),
                    "user_email" => $token->getClaim('email'),
                    "user_alias" => $token->getClaim('alias'),
                    "user_karma" => $token->getClaim('karma'),
                ]);

                return $next($request, $response);
            }
        }

        $request = $request->withAttribute("user_id", -1);
        return $next($request, $response);
    }
}