<?php

namespace Hackernews\Entity;

use JsonSerializable;

/**
 * Class ApiResponse
 *
 * @package Hackernews\Entity
 */
class ApiResponse implements JsonSerializable
{
    /**
     * @var
     */
    private $code;

    /**
     * @var
     */
    private $message;

    /**
     * @var
     */
    private $data;

    /**
     * ApiResponse constructor.
     *
     * @param int $code
     * @param String $message
     * @param mixed $data
     */
    public function __construct(int $code, string $message = null, $data)
    {
        $this->code = $code;
        $this->message = $message;
        $this->data = $data;
    }

    /**
     * @return mixed
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param mixed $code
     */
    public function setCode($code)
    {
        $this->code = $code;
    }

    /**
     * @return mixed
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param mixed $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param mixed $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }

    /**
     * Specify data which should be serialized to JSON
     *
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    function jsonSerialize()
    {

        $object = [];

        $object['code'] = $this->code;
        $object['data'] = $this->data;

        if ($this->code !== 0) {
            $object['message'] = $this->message;
        }

        return $object;
    }
}