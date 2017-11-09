<?php
/**
 * Created by PhpStorm.
 * User: Ebbe
 * Date: 9/24/2017
 * Time: 2:09 PM
 */

namespace Hackernews\Http\Controllers;


use Exception;
use Hackernews\Exceptions\NoCommentsException;
use Hackernews\Exceptions\NoUserException;
use Hackernews\Exceptions\WrongValueException;
use Hackernews\Facade\CommentFacade;
use Hackernews\Http\Handlers\ResponseHandler;
use Hackernews\Logging\ApiLogger;
use Hackernews\Logging\ExceptionLogger;
use PDOException;
use Slim\Http\Request;
use Slim\Http\Response;

class CommentController
{
    /**
     * @param \Slim\Http\Request $request
     * @param \Slim\Http\Response $response
     * @return \Slim\Http\Response
     */
    public function getComments(Request $request, Response $response)
    {
        try {
            ApiLogger::Instance()->logEndpointEvent("info", $request);

            $id = $request->getAttribute('id');
            $userRef = $request->getAttribute('user_id');

            $limit = $request->getParam('limit');
            $page = $request->getParam('page');

            $commentFacade = new CommentFacade();

            //If limit and page is in the request object, the query is called with them as parameters. Otherwise limit and page is set to 5 and 1.
            if ($limit && $page) {
                $comments = $commentFacade->getCommentByPostId($id, $userRef, $limit, $page);
            } else {
                $comments = $commentFacade->getCommentByPostId($id, $userRef);
            }

            return $response->withJson(ResponseHandler::success($comments), 200);
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
    public function createComment(Request $request, Response $response)
    {
        try {
            ApiLogger::Instance()->logEndpointEvent("info", $request);

            $json = $request->getParsedBody();
            $user = $request->getAttribute('user_id');

            $content = $json['content'];
            $post = $json['post_ref'];
            $commentRef = $json['comment_ref'];

            $commentFacade = new CommentFacade();

            if ($commentRef == 0) {
                $result = $commentFacade->postComment($user, $post, $content);
            } else {
                $result = $commentFacade->postComment($user, $post, $content, $commentRef);
            }

            return $response->withStatus(200)->withJson(ResponseHandler::success($result));
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
    public function upvoteComment(Request $request, Response $response)
    {
        $commentFacade = new CommentFacade();

        try {
            ApiLogger::Instance()->logEndpointEvent("info", $request);

            $json = $request->getParsedBody();
            $userRef = $request->getAttribute('user_id');
            $commentRef = $json['comment_ref'];

            $result = $commentFacade->upvote($userRef, $commentRef);

            return $response->withJson(ResponseHandler::success($result), 200);

        } catch (NoCommentsException $e) {
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

    /**
     * @param \Slim\Http\Request $request
     * @param \Slim\Http\Response $response
     * @return \Slim\Http\Response
     */
    public function downvoteComment(Request $request, Response $response)
    {
        $commentFacade = new CommentFacade();

        try {
            ApiLogger::Instance()->logEndpointEvent("info", $request);

            $json = $request->getParsedBody();
            $userRef = $request->getAttribute('user_id');
            $commentRef = $json['comment_ref'];

            $result = $commentFacade->downvote($userRef, $commentRef);

            return $response->withJson(ResponseHandler::success($result), 200);

        } catch (NoCommentsException $e) {
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