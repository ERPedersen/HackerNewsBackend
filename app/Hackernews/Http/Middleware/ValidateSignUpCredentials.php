<?php
/**
 * Created by PhpStorm.
 * User: Emil
 * Date: 23-09-2017
 * Time: 15:23
 */

namespace Hackernews\Http\Middleware;

use Hackernews\Exceptions\ValidationException;
use Hackernews\Http\Handlers\ResponseHandler;
use Hackernews\Validation\UserValidator;
use Slim\Http\Request;
use Slim\Http\Response;

class ValidateSignUpCredentials
{
    function __invoke(Request $request, Response $response, $next)
    {
        try {
            $json = $request->getParsedBody();
            $errors = [];

            if (empty($json['email'])) {
                array_push($errors, "Please provide an email");
            } else {
                if (! UserValidator::validateEmail($json['email'])) {
                    array_push($errors, "Please provide a valid email");
                }
            }

            if (empty($json['password'])) {
                array_push($errors, "Please provide a password");
            } else {
                if (! UserValidator::validatePassword($json['password'])) {
                    array_push($errors, "Please provide a valid password");
                }
            }

            if (empty($json['alias'])) {
                array_push($errors, "Please provide an alias");
            } else {
                if (! UserValidator::validatePassword($json['alias'])) {
                    array_push($errors, "Please provide a valid alias");
                }
            }

            if (! empty($errors)) {
                throw new ValidationException("Validation error", 4, $errors);
            }

            return $next($request, $response);
        } catch (ValidationException $ve) {
            return $response->withJson(ResponseHandler::errorWithMessages($ve), 400);
        }
    }
}