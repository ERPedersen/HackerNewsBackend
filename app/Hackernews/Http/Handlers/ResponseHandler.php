<?php
/**
 * Created by PhpStorm.
 * User: Emil
 * Date: 16-09-2017
 * Time: 18:33
 */

namespace Hackernews\Http\Handlers;

use Hackernews\Entity\ApiResponse;

class ResponseHandler
{
    public static function success($data)
    {
        return new ApiResponse(0, null, $data);
    }

    public static function error(int $code, string $message, $data)
    {
        return new ApiResponse($code, $message, $data);
    }
}