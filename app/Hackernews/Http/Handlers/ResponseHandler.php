<?php
/**
 * Created by PhpStorm.
 * User: Emil
 * Date: 16-09-2017
 * Time: 18:33
 */

namespace Hackernews\Http\Handlers;

use Hackernews\Entity\ApiResponse;

/**
 * Class ResponseHandler
 *
 * @package Hackernews\Http\Handlers
 */
class ResponseHandler
{
    /**
     * @param $data
     * @return \Hackernews\Entity\ApiResponse
     */
    public static function success($data)
    {
        return new ApiResponse(0, null, $data);
    }

    /**
     * @param int $code
     * @param string $message
     * @param $data
     * @return \Hackernews\Entity\ApiResponse
     */
    public static function error(int $code, string $message, $data)
    {
        return new ApiResponse($code, $message, $data);
    }
}