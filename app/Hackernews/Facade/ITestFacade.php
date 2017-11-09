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
     * @param $hanesst_id
     */
    public function updateHanesstId($hanesst_id);

    /**
     * @return Hanesst
     */
    public function getHanesstId(): Hanesst;
}