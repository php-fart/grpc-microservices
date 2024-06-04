<?php

namespace GRPC\Services\Payment\v1;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\GPBUtil;
use Google\Protobuf\Internal\RepeatedField;

/**
 * Generated from protobuf message <code>payment.v1.request.ChargeRequest</code>
 */
class ChargeRequest extends \Google\Protobuf\Internal\Message
{
    /** Generated from protobuf field <code>.payment.v1.dto.Payment payment = 1;</code> */
    protected $payment = null;

    /**
     * Constructor.
     *
     * @param array $data {
     *     Optional. Data for populating the Message object.
     *
     *     @type \GRPC\Services\Payment\v1\Payment $payment
     * }
     */
    public function __construct($data = null)
    {
        \GRPC\ProtobufMetadata\Payment\v1\Request::initOnce();
        parent::__construct($data);
    }

    /**
     * Generated from protobuf field <code>.payment.v1.dto.Payment payment = 1;</code>
     * @return \GRPC\Services\Payment\v1\Payment|null
     */
    public function getPayment()
    {
        return isset($this->payment) ? $this->payment : null;
    }

    public function hasPayment()
    {
        return isset($this->payment);
    }

    public function clearPayment()
    {
        unset($this->payment);
    }

    /**
     * Generated from protobuf field <code>.payment.v1.dto.Payment payment = 1;</code>
     * @param \GRPC\Services\Payment\v1\Payment $var
     * @return $this
     */
    public function setPayment($var)
    {
        GPBUtil::checkMessage($var, Payment::class);
        $this->payment = $var;

        return $this;
    }
}
