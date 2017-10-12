<?php
/**
 * Created by PhpStorm.
 * User: Ebbe
 * Date: 9/24/2017
 * Time: 2:09 PM
 */

namespace Hackernews\Http\Controllers;


use Exception;
use Hackernews\Facade\CommentFacade;
use Hackernews\Http\Handlers\ResponseHandler;
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
            $id = $request->getAttribute('id');

            $limit = $request->getParam('limit');
            $page = $request->getParam('page');

            $commentFacade = new CommentFacade();

            //If limit and page is in the request object, the query is called with them as parameters. Otherwise limit and page is set to 5 and 1.
            if ($limit && $page) {
                $comments = $commentFacade->getCommentByPostId($id, $limit, $page);
            } else {
                $comments = $commentFacade->getCommentByPostId($id);
            }

            return $response->withJson(ResponseHandler::success($comments), 200);
        } catch (Exception $e) {
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

            $json = $request->getParsedBody();

            $content = $json['content'];
            $user = $json['user_ref'];
            $post = $json['post_ref'];
            $commentRef = $json['comment_ref'];

            $commentFacade = new CommentFacade();

            if ($commentRef == 0) {
                $result = $commentFacade->postComment($user, $post, $content);
            } else {
                $result = $commentFacade->postComment($user, $post, $content, $commentRef);
            }


            return $response->withStatus(200)->withJson(ResponseHandler::success($result));
        } catch (Exception $e) {
            return $response->withStatus(500)->withJson(ResponseHandler::error($e));
        }

    }
}