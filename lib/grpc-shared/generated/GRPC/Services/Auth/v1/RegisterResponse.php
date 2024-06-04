<?php

namespace GRPC\Services\Auth\v1;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\GPBUtil;
use Google\Protobuf\Internal\RepeatedField;

/**
 * Generated from protobuf message <code>auth.v1.response.RegisterResponse</code>
 */
class RegisterResponse extends \Google\Protobuf\Internal\Message
{
    /** Generated from protobuf field <code>.auth.v1.dto.Token token = 1;</code> */
    protected $token = null;

    /**
     * Constructor.
     *
     * @param array $data {
     *     Optional. Data for populating the Message object.
     *
     *     @type \GRPC\Services\Auth\v1\Token $token
     * }
     */
    public function __construct($data = null)
    {
        \GRPC\ProtobufMetadata\Auth\v1\Response::initOnce();
        parent::__construct($data);
    }

    /**
     * Generated from protobuf field <code>.auth.v1.dto.Token token = 1;</code>
     * @return \GRPC\Services\Auth\v1\Token|null
     */
    public function getToken()
    {
        return isset($this->token) ? $this->token : null;
    }

    public function hasToken()
    {
        return isset($this->token);
    }

    public function clearToken()
    {
        unset($this->token);
    }

    /**
     * Generated from protobuf field <code>.auth.v1.dto.Token token = 1;</code>
     * @param \GRPC\Services\Auth\v1\Token $var
     * @return $this
     */
    public function setToken($var)
    {
        GPBUtil::checkMessage($var, Token::class);
        $this->token = $var;

        return $this;
    }
}
