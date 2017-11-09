<?php

namespace Hackernews\Http\Middleware;

use Hackernews\Exceptions\MissingHanesstIdException;
use Hackernews\Http\Handlers\ResponseHandler;
use Slim\Http\Request;
use Slim\Http\Response;

class ValidateTestPostValues
{

    function __invoke(Request $request, Response $response, $next)
    {
        $json = $request->getParsedBody();

        try {

            if (empty($json['hanesst_id'])) {
                throw new MissingHanesstIdException("You must provide a hanesst_id", 4);
            } else {
                $hanesstId = $json['hanesst_id'];
            }

            $title = "This is a test";
            $content = "This is a test";
            $userId = 911121;

            if (!empty($json['post_title'])) {
                $title = $json['post_title'];
            }

            if (!empty($json['post_text'])) {
                $content = $json['post_text'];
            }

            $request = $request->withAttributes([
                "title" => $title,
                "content" => $content,
                "user_id" => $userId,
                "hanesst_id" => $hanesstId
            ]);

            return $next($request, $response);

        } catch (MissingHanesstIdException $e) {
            return $response->withJson(ResponseHandler::error($e));
        }
    }
}