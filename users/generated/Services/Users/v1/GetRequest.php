<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: users/v1/request.proto

namespace GRPC\Services\Users\v1;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 * Generated from protobuf message <code>users.v1.request.GetRequest</code>
 */
class GetRequest extends \Google\Protobuf\Internal\Message
{
    /**
     * Generated from protobuf field <code>string uuid = 1;</code>
     */
    protected $uuid = '';

    /**
     * Constructor.
     *
     * @param array $data {
     *     Optional. Data for populating the Message object.
     *
     *     @type string $uuid
     * }
     */
    public function __construct($data = NULL) {
        \GRPC\ProtobufMetadata\Users\v1\Request::initOnce();
        parent::__construct($data);
    }

    /**
     * Generated from protobuf field <code>string uuid = 1;</code>
     * @return string
     */
    public function getUuid()
    {
        return $this->uuid;
    }

    /**
     * Generated from protobuf field <code>string uuid = 1;</code>
     * @param string $var
     * @return $this
     */
    public function setUuid($var)
    {
        GPBUtil::checkString($var, True);
        $this->uuid = $var;

        return $this;
    }

}
