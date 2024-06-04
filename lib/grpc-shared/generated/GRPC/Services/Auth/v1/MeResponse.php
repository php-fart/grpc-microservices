<?php

namespace GRPC\Services\Auth\v1;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\GPBUtil;
use Google\Protobuf\Internal\RepeatedField;

/**
 * Generated from protobuf message <code>auth.v1.response.MeResponse</code>
 */
class MeResponse extends \Google\Protobuf\Internal\Message
{
    /** Generated from protobuf field <code>.users.v1.dto.User user = 1;</code> */
    protected $user = null;

    /**
     * Constructor.
     *
     * @param array $data {
     *     Optional. Data for populating the Message object.
     *
     *     @type \GRPC\Services\Users\v1\User $user
     * }
     */
    public function __construct($data = null)
    {
        \GRPC\ProtobufMetadata\Auth\v1\Response::initOnce();
        parent::__construct($data);
    }

    /**
     * Generated from protobuf field <code>.users.v1.dto.User user = 1;</code>
     * @return \GRPC\Services\Users\v1\User|null
     */
    public function getUser()
    {
        return isset($this->user) ? $this->user : null;
    }

    public function hasUser()
    {
        return isset($this->user);
    }

    public function clearUser()
    {
        unset($this->user);
    }

    /**
     * Generated from protobuf field <code>.users.v1.dto.User user = 1;</code>
     * @param \GRPC\Services\Users\v1\User $var
     * @return $this
     */
    public function setUser($var)
    {
        GPBUtil::checkMessage($var, \GRPC\Services\Users\v1\User::class);
        $this->user = $var;

        return $this;
    }
}
