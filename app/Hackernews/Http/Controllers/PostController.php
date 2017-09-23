<?php

namespace Hackernews\Http\Controllers;

use Exception;
use Hackernews\Exceptions\DuplicatePostException;
use Hackernews\Facade\PostFacade;
use Hackernews\Http\Handlers\ResponseHandler;
use Slim\Http\Request;
use Slim\Http\Response;

/**
 * Class PostController
 *
 * @package Hackernews\Http\Controllers
 */
class PostController
{
    /**
     * @param \Slim\Http\Request $request
     * @param \Slim\Http\Response $response
     * @return \Slim\Http\Response
     */
    public function createPost(Request $request, Response $response)
    {

        $json = $request->getParsedBody();
        $userRef = $request->getAttribute('user_id');

        $title = $json['title'];
        $url = $json['url'];

        try {
            $postFacade = new PostFacade();
            $result = $postFacade->createPost($title, $url, $userRef);

            return $response->withJson(ResponseHandler::success($result));
        } catch (DuplicatePostException $e) {
            return $response->withStatus(409)->withJson(ResponseHandler::error($e));
        } catch (Exception $e) {
            return $response->withStatus(500)->withJson(ResponseHandler::error($e));
        }
    }
}