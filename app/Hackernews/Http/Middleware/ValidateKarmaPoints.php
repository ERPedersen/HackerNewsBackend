<?php

namespace Hackernews\Http\Middleware;

use Hackernews\Exceptions\KarmaException;
use Hackernews\Facade\UserFacade;
use Hackernews\Http\Handlers\ResponseHandler;
use Slim\Http\Request;
use Slim\Http\Response;

/**
 * Class ValidateKarmaPoints
 *
 * @package Hackernews\Http\Middleware
 */
class ValidateKarmaPoints
{
    /**
     * @param \Slim\Http\Request $request
     * @param \Slim\Http\Response $response
     * @param $next
     * @return \Slim\Http\Response
     */
    public function __invoke(Request $request, Response $response, $next)
    {
        $id = $request->getAttribute('user_id');

        $userFacade = new UserFacade();
        $user = $userFacade->getUserData($id);

        if($user->getKarma() < 50) {

            return $response->withStatus(405)->withJson(ResponseHandler::error(new KarmaException("Your karma score is to low to be able to downvote, minimum 50 karma required.", 5)));
        }

        return $next($request, $response);
    }
}