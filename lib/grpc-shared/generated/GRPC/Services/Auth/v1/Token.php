<?php

namespace GRPC\Services\Auth\v1;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\GPBUtil;
use Google\Protobuf\Internal\RepeatedField;

/**
 * Generated from protobuf message <code>auth.v1.dto.Token</code>
 */
class Token extends \Google\Protobuf\Internal\Message
{
    /** Generated from protobuf field <code>string token = 1;</code> */
    protected $token = '';

    /**
     * auth, refresh, 2fa, password_reset
     *
     * Generated from protobuf field <code>string type = 2;</code>
     */
    protected $type = '';

    /** Generated from protobuf field <code>.google.protobuf.Timestamp expires_at = 3;</code> */
    protected $expires_at = null;

    /**
     * Constructor.
     *
     * @param array $data {
     *     Optional. Data for populating the Message object.
     *
     *     @type string $token
     *     @type string $type
     *           auth, refresh, 2fa, password_reset
     *     @type \Google\Protobuf\Timestamp $expires_at
     * }
     */
    public function __construct($data = null)
    {
        \GRPC\ProtobufMetadata\Auth\v1\Message::initOnce();
        parent::__construct($data);
    }

    /**
     * Generated from protobuf field <code>string token = 1;</code>
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Generated from protobuf field <code>string token = 1;</code>
     * @param string $var
     * @return $this
     */
    public function setToken($var)
    {
        GPBUtil::checkString($var, True);
        $this->token = $var;

        return $this;
    }

    /**
     * auth, refresh, 2fa, password_reset
     *
     * Generated from protobuf field <code>string type = 2;</code>
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * auth, refresh, 2fa, password_reset
     *
     * Generated from protobuf field <code>string type = 2;</code>
     * @param string $var
     * @return $this
     */
    public function setType($var)
    {
        GPBUtil::checkString($var, True);
        $this->type = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>.google.protobuf.Timestamp expires_at = 3;</code>
     * @return \Google\Protobuf\Timestamp|null
     */
    public function getExpiresAt()
    {
        return isset($this->expires_at) ? $this->expires_at : null;
    }

    public function hasExpiresAt()
    {
        return isset($this->expires_at);
    }

    public function clearExpiresAt()
    {
        unset($this->expires_at);
    }

    /**
     * Generated from protobuf field <code>.google.protobuf.Timestamp expires_at = 3;</code>
     * @param \Google\Protobuf\Timestamp $var
     * @return $this
     */
    public function setExpiresAt($var)
    {
        GPBUtil::checkMessage($var, \Google\Protobuf\Timestamp::class);
        $this->expires_at = $var;

        return $this;
    }
}
