<?php

namespace Hackernews\Http\Controllers;

use Exception;
use Hackernews\Exceptions\DuplicatePostException;
use Hackernews\Exceptions\NoPostsException;
use Hackernews\Exceptions\NoUserException;
use Hackernews\Exceptions\WrongValueException;
use Hackernews\Facade\CommentFacade;
use Hackernews\Facade\PostFacade;
use Hackernews\Http\Handlers\ResponseHandler;
use Hackernews\Logging\ApiLogger;
use Hackernews\Logging\ExceptionLogger;
use PDOException;
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
        try {
            ApiLogger::Instance()->logEndpointEvent("info", $request);

            $json = $request->getParsedBody();
            $postType = $request->getAttribute('post_type');
            $userRef = $request->getAttribute('user_id');
            $title = $json['title'];

            $postFacade = new PostFacade();

            if ($postType === 'story') {
                $content = $json['content'];
                $result = $postFacade->createStory($title, $content, $userRef);
            } else if ($postType === 'link') {
                $url = $json['url'];
                $result = $postFacade->createPost($title, $url, $userRef);
            } else {
                return $response->withStatus(500)->withJson(ResponseHandler::error(new Exception("An unexpected error occured")));
            }

            return $response->withJson(ResponseHandler::success($result));
        } catch (DuplicatePostException $e) {
            ExceptionLogger::Instance()->logEndpointException($e, 'warning', $request);
            return $response->withStatus(409)->withJson(ResponseHandler::error($e));
        } catch (PDOException $e) {
            ExceptionLogger::Instance()->logEndpointException($e, 'error', $request);
            return $response->withStatus(500)->withJson(ResponseHandler::dbError());
        } catch (Exception $e) {
            ExceptionLogger::Instance()->logEndpointException($e, 'error', $request);
            return $response->withStatus(500)->withJson(ResponseHandler::error($e));
        }
    }

    /**
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function getPosts(Request $request, Response $response)
    {
        try {
            ApiLogger::Instance()->logEndpointEvent("info", $request);

            $limit = $request->getParam('limit');
            $lastId = $request->getParam('page');
            $userRef = $request->getAttribute("user_id");

            $postFacade = new PostFacade();

            if ($limit && $lastId) {
                $result = $postFacade->getPosts($limit, $lastId, $userRef);
            } else {
                $result = $postFacade->getPosts(5, -1, $userRef);
            }

            return $response->withJson(ResponseHandler::success($result));
        } catch (NoPostsException $e) {
            ExceptionLogger::Instance()->logEndpointException($e, 'notice', $request);
            return $response->withStatus(200)->withJson(ResponseHandler::success([]));
        } catch (PDOException $e) {
            ExceptionLogger::Instance()->logEndpointException($e, 'error', $request);
            return $response->withStatus(500)->withJson(ResponseHandler::dbError());
        } catch (Exception $e) {
            ExceptionLogger::Instance()->logEndpointException($e, 'error', $request);
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
            ApiLogger::Instance()->logEndpointEvent("info", $request);

            $slug = $request->getAttribute('slug');
            $userRef = $request->getAttribute("user_id");

            $postFacade = new PostFacade();
            $commentFacade = new CommentFacade();

            $post = $postFacade->getPostBySlug($slug, $userRef);
            $comments = $commentFacade->getCommentByPostId($post->getId(), $userRef);

            $data = [
                'post' => $post,
                'comments' => $comments
            ];

            return $response->withJson(ResponseHandler::success($data), 200);
        } catch (NoPostsException $e) {
            ExceptionLogger::Instance()->logEndpointException($e, 'notice', $request);
            return $response->withStatus(204);
        } catch (PDOException $e) {
            ExceptionLogger::Instance()->logEndpointException($e, 'error', $request);
            return $response->withStatus(500)->withJson(ResponseHandler::dbError());
        } catch (Exception $e) {
            ExceptionLogger::Instance()->logEndpointException($e, 'error', $request);
            return $response->withStatus(500)->withJson(ResponseHandler::error($e));
        }
    }

    /**
     * @param \Slim\Http\Request $request
     * @param \Slim\Http\Response $response
     * @return \Slim\Http\Response
     */
    public function upvotePost(Request $request, Response $response)
    {
        try {
            ApiLogger::Instance()->logEndpointEvent("info", $request);

            $postFacade = new PostFacade();
            $json = $request->getParsedBody();
            $userRef = $request->getAttribute('user_id');
            $postRef = $json['post_ref'];

            $result = $postFacade->upvote($userRef, $postRef);

            return $response->withJson(ResponseHandler::success($result), 200);

        } catch (NoPostsException $e) {
            ExceptionLogger::Instance()->logEndpointException($e, 'notice', $request);
            return $response->withStatus(204);
        } catch (NoUserException $e) {
            ExceptionLogger::Instance()->logEndpointException($e, 'notice', $request);
            return $response->withStatus(204);
        } catch (WrongValueException $e) {
            ExceptionLogger::Instance()->logEndpointException($e, 'error', $request);
            return $response->withStatus(500);
        } catch (PDOException $e) {
            ExceptionLogger::Instance()->logEndpointException($e, 'error', $request);
            return $response->withStatus(500)->withJson(ResponseHandler::dbError());
        } catch (Exception $e) {
            ExceptionLogger::Instance()->logEndpointException($e, 'error', $request);
            return $response->withStatus(500)->withJson(ResponseHandler::error($e));
        }
    }

    /**
     * @param \Slim\Http\Request $request
     * @param \Slim\Http\Response $response
     * @return \Slim\Http\Response
     */
    public function downvotePost(Request $request, Response $response)
    {
        try {
            ApiLogger::Instance()->logEndpointEvent("info", $request);

            $postFacade = new PostFacade();
            $json = $request->getParsedBody();
            $userRef = $request->getAttribute('user_id');
            $postRef = $json['post_ref'];

            $result = $postFacade->downvote($userRef, $postRef);

            return $response->withJson(ResponseHandler::success($result), 200);

        } catch (NoPostsException $e) {
            ExceptionLogger::Instance()->logEndpointException($e, 'notice', $request);
            return $response->withStatus(204);
        } catch (NoUserException $e) {
            ExceptionLogger::Instance()->logEndpointException($e, 'notice', $request);
            return $response->withStatus(204);
        } catch (WrongValueException $e) {
            ExceptionLogger::Instance()->logEndpointException($e, 'error', $request);
            return $response->withStatus(500)->withJson(ResponseHandler::error($e));
        } catch (PDOException $e) {
            ExceptionLogger::Instance()->logEndpointException($e, 'error', $request);
            return $response->withStatus(500)->withJson(ResponseHandler::dbError());
        } catch (Exception $e) {
            ExceptionLogger::Instance()->logEndpointException($e, 'error', $request);
            return $response->withStatus(500)->withJson(ResponseHandler::error($e));
        }
    }
}