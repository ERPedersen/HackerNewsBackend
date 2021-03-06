<?php

namespace Hackernews\Http\Middleware;

use Hackernews\Exceptions\ValidationException;
use Hackernews\Http\Handlers\ResponseHandler;
use Hackernews\Validation\PostValidator;
use Hackernews\Validation\UserValidator;
use Slim\Http\Request;
use Slim\Http\Response;

class ValidateCreatePostCredentials
{
    /**
     * Validates the required parameters when performing a login
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

            if (empty($json['title'])) {
                array_push($errors, "Please provide a title for your post");
            } else {
                if (!PostValidator::validateTitle($json['title'])) {
                    array_push($errors, "Please provide a valid title for your post");
                }
            }

            if (!empty($json['url'])) {
                if (!PostValidator::validateUrl($json['url'])) {
                    array_push($errors, "Please provde a valid url for your post");
                } else {
                    $request = $request->withAttribute('post_type', 'link');
                }
            } else if (!empty($json['content'])) {
                if (!PostValidator::validateContent($json['content'])) {
                    array_push($errors, "Please provde some valid content for your post");
                } else {
                    $request = $request->withAttribute('post_type', 'story');
                }
            }

            if (empty($json['url']) && empty($json['content'])) {
                array_push($errors, "Please provide either a url or some content for your post.");
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