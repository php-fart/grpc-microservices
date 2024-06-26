<?php

namespace GRPC\Services\Common\v1;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\GPBUtil;
use Google\Protobuf\Internal\RepeatedField;

/**
 * Generated from protobuf message <code>common.v1.dto.Exception</code>
 */
class Exception extends \Google\Protobuf\Internal\Message
{
    /** Generated from protobuf field <code>string message = 1;</code> */
    protected $message = '';

    /** Generated from protobuf field <code>string code = 2;</code> */
    protected $code = '';

    /** Generated from protobuf field <code>string class = 3;</code> */
    protected $class = '';

    /**
     * Constructor.
     *
     * @param array $data {
     *     Optional. Data for populating the Message object.
     *
     *     @type string $message
     *     @type string $code
     *     @type string $class
     * }
     */
    public function __construct($data = null)
    {
        \GRPC\ProtobufMetadata\Common\v1\Message::initOnce();
        parent::__construct($data);
    }

    /**
     * Generated from protobuf field <code>string message = 1;</code>
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Generated from protobuf field <code>string message = 1;</code>
     * @param string $var
     * @return $this
     */
    public function setMessage($var)
    {
        GPBUtil::checkString($var, True);
        $this->message = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>string code = 2;</code>
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Generated from protobuf field <code>string code = 2;</code>
     * @param string $var
     * @return $this
     */
    public function setCode($var)
    {
        GPBUtil::checkString($var, True);
        $this->code = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>string class = 3;</code>
     * @return string
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * Generated from protobuf field <code>string class = 3;</code>
     * @param string $var
     * @return $this
     */
    public function setClass($var)
    {
        GPBUtil::checkString($var, True);
        $this->class = $var;

        return $this;
    }
}
