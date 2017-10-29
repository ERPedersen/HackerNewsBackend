<?php

namespace Hackernews\Http\Middleware;

use Hackernews\Validation\CommentValidator;
use Hackernews\Exceptions\ValidationException;
use Hackernews\Http\Handlers\ResponseHandler;
use Slim\Http\Request;
use Slim\Http\Response;

class ValidateCreateCommentCredentials
{
    /**
     * Validates the required parameters when posting a comment.
     *
     * @param \Slim\Http\Request $request
     * @param \Slim\Http\Response $response
     * @param $next
     * @return \Slim\Http\Response
     */
    function __invoke(Request $request, Response $response, $next)
    {
        try {
            $json = $request->getParsedBody();
            $errors = [];

            if (empty($json['content'])) {
                array_push($errors, "Please provide text in your comment.");
            } else {
                if (!CommentValidator::validateBody($json['content'])) {
                    array_push($errors, "Please provide valid text in your comment.");
                }
            }

            if (empty($request->getAttribute('user_id'))) {
                array_push($errors, "User not found. Are you logged in?");
            }

            if (empty($json['post_ref'])) {
                array_push($errors, "Post not found. It may have been deleted while your comment was being written.");
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