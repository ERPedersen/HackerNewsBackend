<?php

namespace Hackernews\Http\Controllers;

use Exception;
use Hackernews\Exceptions\DuplicatePostException;
use Hackernews\Exceptions\NoPostsException;
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

    public function getPosts(Request $request, Response $response)
    {

        $limit = $request->getParam('limit');
        $page = $request->getParam('page');

        try {
            $postFacade = new PostFacade();

            if ($limit && $page) {
                $result = $postFacade->getPosts($limit, $page);
            } else {
                $result = $postFacade->getPosts();
            }

            return $response->withJson(ResponseHandler::success($result));
        } catch (NoPostsException $e) {
            return $response->withStatus(200)->withJson(ResponseHandler::success([]));
        } catch (Exception $e) {
            return $response->withStatus(500)->withJson(ResponseHandler::error($e));
        }
    }

    /**
     * @param \Slim\Http\Request $request
     * @param \Slim\Http\Response $response
     * @return \Slim\Http\Response
     */
    public function getPost(Request $request, Response $response)
    {
        try {
            $slug = $request->getAttribute('slug');
            $postFacade = new PostFacade();

            $post = $postFacade->getPostBySlug($slug);

            return $response->withJson(ResponseHandler::success($post), 200);
        } catch (Exception $e) {
            return $response->withStatus(500)->withJson(ResponseHandler::error($e));
        }
    }
}