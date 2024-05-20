<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: users/v1/request.proto

namespace GRPC\Services\Users\v1;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 * Generated from protobuf message <code>users.v1.request.ListRequest</code>
 */
class ListRequest extends \Google\Protobuf\Internal\Message
{
    /**
     * Generated from protobuf field <code>uint32 page = 1;</code>
     */
    protected $page = 0;
    /**
     * Generated from protobuf field <code>uint32 limit = 2;</code>
     */
    protected $limit = 0;

    /**
     * Constructor.
     *
     * @param array $data {
     *     Optional. Data for populating the Message object.
     *
     *     @type int $page
     *     @type int $limit
     * }
     */
    public function __construct($data = NULL) {
        \GRPC\ProtobufMetadata\Users\v1\Request::initOnce();
        parent::__construct($data);
    }

    /**
     * Generated from protobuf field <code>uint32 page = 1;</code>
     * @return int
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * Generated from protobuf field <code>uint32 page = 1;</code>
     * @param int $var
     * @return $this
     */
    public function setPage($var)
    {
        GPBUtil::checkUint32($var);
        $this->page = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>uint32 limit = 2;</code>
     * @return int
     */
    public function getLimit()
    {
        return $this->limit;
    }

    /**
     * Generated from protobuf field <code>uint32 limit = 2;</code>
     * @param int $var
     * @return $this
     */
    public function setLimit($var)
    {
        GPBUtil::checkUint32($var);
        $this->limit = $var;

        return $this;
    }

}
