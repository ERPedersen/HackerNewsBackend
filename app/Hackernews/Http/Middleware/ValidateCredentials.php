<?php

namespace Hackernews\Http\Middleware;


use Hackernews\Exceptions\ValidationException;
use Hackernews\Http\Handlers\ResponseHandler;
use Slim\Http\Response;
use Slim\Http\Request;

class ValidateCredentials
{
    /**
     * Validates the required parameters when upvoting a post.
     *
     * @param \Slim\Http\Request $request
     * @param \Slim\Http\Response $response
     * @param $next
     * @return \Slim\Http\Response
     */
    function __invoke(Request $request, Response $response, $next) {
        try {
            $json = $request->getParsedBody();
            $errors = [];

            if (empty($json['postRef'])) {
                array_push($errors, "Please provide a valid post reference for your upvote");
            } else {
                if(!preg_match('/^\d+$/', $json['postRef'])) {
                    array_push($errors, "Post reference must be an integer");
                }
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