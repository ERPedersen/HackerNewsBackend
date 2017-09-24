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
            $commentFacade = new CommentFacade();

            $comments = $commentFacade->getCommentByPostId($id);
            return $response->withJson(ResponseHandler::success($comments), 200);
        } catch (Exception $e) {
            return $response->withStatus(500)->withJson(ResponseHandler::error($e));
        }
    }
}