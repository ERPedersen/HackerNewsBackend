<?php

namespace Hackernews\Facade;

use Hackernews\Entity\Hanesst;
use Slim\Http\Request;
use Slim\Http\Response;

/**
 * Interface ITestFacade
 *
 * @package Hackernews\Facade
 */
interface ITestFacade
{

    /**
     * @param Request $request
     * @return Request
     */
    public function refactorInitialRequest(Request $request): Request;

    /**
     * @param Request $request
     * @param Response $response
     * @return Request
     */
    public function addTokenToHeader(Request $request, Response $response): Request;

    /**
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function postRequest(Request $request, Response $response): Response;

    /**
     * @param Request $request
     * @param Response $response
     * @return Hanesst
     */
    public function latestHanesst(Request $request, Response $response): Hanesst;
}