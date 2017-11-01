<?php

namespace Hackernews\Entity;

use JsonSerializable;


class Hanesst implements JsonSerializable
{
    /**
     * @var
     */
    private $hanesstId;


    /**
     * Hanesst constructor.
     * @param $hanesstId
     */
    public function __construct($hanesstId)
    {
        $this->hanesstId = $hanesstId;
    }

    /**
     * @return mixed
     */
    public function getHanesstId()
    {
        return $this->hanesstId;
    }

    /**
     * @param mixed $hanesstId
     */
    public function setHanesstId($hanesstId)
    {
        $this->hanesstId = $hanesstId;
    }


    /**
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize()
    {
        $hanesst = [];
        $hanesst['id'] = $this->hanesstId;

        return $hanesst;
    }
}