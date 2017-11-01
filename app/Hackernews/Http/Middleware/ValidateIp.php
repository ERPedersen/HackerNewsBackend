<?php
/**
 * Created by PhpStorm.
 * User: denni
 * Date: 10/30/2017
 * Time: 12:12 PM
 */

namespace Hackernews\Http\Middleware;


use Hackernews\Exceptions\UnauthorizedIpException;
use Hackernews\Http\Handlers\ResponseHandler;
use Slim\Http\Response;
use Slim\Http\Request;

class ValidateIp
{

    /**
     * Method that checks the request ip. If not from Helge's ip then block the request.
     *
     * @param \Slim\Http\Request $request
     * @param \Slim\Http\Response $response
     * @param $next
     * @return \Slim\Http\Response
     */
    function __invoke(Request $request, Response $response, $next)
    {
        try {
            $ipAddress = $request->getAttribute('ip_address');

            if(!($ipAddress == "138.68.91.198" || $ipAddress == "192.168.10.1")) {
                throw new UnauthorizedIpException("You are forbidden to access this endpoint. Ip: " . $ipAddress, 1);
            }

            return $next($request, $response);

        } catch (UnauthorizedIpException $ue) {

            return $response->withJson(ResponseHandler::error($ue), 403);
        }
    }

}