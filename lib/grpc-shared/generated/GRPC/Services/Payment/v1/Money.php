<?php

namespace GRPC\Services\Payment\v1;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\GPBUtil;
use Google\Protobuf\Internal\RepeatedField;

/**
 * Generated from protobuf message <code>payment.v1.dto.Money</code>
 */
class Money extends \Google\Protobuf\Internal\Message
{
    /** Generated from protobuf field <code>string currency_code = 1;</code> */
    protected $currency_code = '';

    /** Generated from protobuf field <code>int64 amount = 2;</code> */
    protected $amount = 0;

    /**
     * Constructor.
     *
     * @param array $data {
     *     Optional. Data for populating the Message object.
     *
     *     @type string $currency_code
     *     @type int|string $amount
     * }
     */
    public function __construct($data = null)
    {
        \GRPC\ProtobufMetadata\Payment\v1\Message::initOnce();
        parent::__construct($data);
    }

    /**
     * Generated from protobuf field <code>string currency_code = 1;</code>
     * @return string
     */
    public function getCurrencyCode()
    {
        return $this->currency_code;
    }

    /**
     * Generated from protobuf field <code>string currency_code = 1;</code>
     * @param string $var
     * @return $this
     */
    public function setCurrencyCode($var)
    {
        GPBUtil::checkString($var, True);
        $this->currency_code = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>int64 amount = 2;</code>
     * @return int|string
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Generated from protobuf field <code>int64 amount = 2;</code>
     * @param int|string $var
     * @return $this
     */
    public function setAmount($var)
    {
        GPBUtil::checkInt64($var);
        $this->amount = $var;

        return $this;
    }
}
