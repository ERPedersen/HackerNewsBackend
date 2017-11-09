<?php
/**
 * Created by PhpStorm.
 * User: Emil
 * Date: 16-09-2017
 * Time: 18:33
 */

namespace Hackernews\Http\Handlers;

use Exception;
use Hackernews\Exceptions\MultiException;
use stdClass;

/**
 * Class ResponseHandler
 *
 * @package Hackernews\Http\Handlers
 */
class ResponseHandler
{
    /**
     * @param $data
     * @return \stdClass
     */
    public static function success($data)
    {
        $response = new stdClass();
        $response->code = 0;
        $response->data = $data;

        return $response;
    }

    /**
     * @param \Hackernews\Exceptions\MultiException $exception
     * @return \stdClass
     */
    public static function errorWithMessages(MultiException $exception)
    {
        $response = new stdClass();
        $response->code = $exception->getCode();
        $response->message = $exception->getMessage();
        $response->errors = $exception->getErrors();

        return $response;
    }

    /**
     * @param \Exception $exception
     * @return \stdClass
     */
    public static function error(Exception $exception)
    {
        $response = new stdClass();
        $response->code = $exception->getCode();
        $response->message = $exception->getMessage();

        return $response;
    }

    /**
     * Forms a response from a custom error message
     *
     * @param $message
     * @param $code
     * @return stdClass
     */
    public static function dbError($message = null, $code = null)
    {
        $response = new stdClass();
        $response->code = isset($message) ? $message : 'An unexpected error occurred';
        $response->message = isset($code) ? $code : 1;

        return $response;
    }
}