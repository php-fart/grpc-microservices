<?php

namespace GRPC\Services\Common\v1;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\GPBUtil;
use Google\Protobuf\Internal\RepeatedField;

/**
 * Generated from protobuf message <code>common.v1.dto.Empty</code>
 */
class PBEmpty extends \Google\Protobuf\Internal\Message
{
    /**
     * Constructor.
     *
     * @param array $data {
     *     Optional. Data for populating the Message object.
     *
     * }
     */
    public function __construct($data = null)
    {
        \GRPC\ProtobufMetadata\Common\v1\Message::initOnce();
        parent::__construct($data);
    }
}
