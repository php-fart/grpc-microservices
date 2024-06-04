<?php

namespace GRPC\Services\Payment\v1;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\GPBUtil;
use Google\Protobuf\Internal\RepeatedField;

/**
 * Generated from protobuf message <code>payment.v1.dto.Payment</code>
 */
class Payment extends \Google\Protobuf\Internal\Message
{
    /** Generated from protobuf field <code>string description = 1;</code> */
    protected $description = '';

    /** Generated from protobuf field <code>string email = 2;</code> */
    protected $email = '';

    /** Generated from protobuf field <code>.payment.v1.dto.Money amount = 3;</code> */
    protected $amount = null;

    /** Generated from protobuf field <code>string payment_method = 4;</code> */
    protected $payment_method = '';

    /** Generated from protobuf field <code>.google.protobuf.Timestamp created_at = 5;</code> */
    protected $created_at = null;

    /**
     * Constructor.
     *
     * @param array $data {
     *     Optional. Data for populating the Message object.
     *
     *     @type string $description
     *     @type string $email
     *     @type \GRPC\Services\Payment\v1\Money $amount
     *     @type string $payment_method
     *     @type \Google\Protobuf\Timestamp $created_at
     * }
     */
    public function __construct($data = null)
    {
        \GRPC\ProtobufMetadata\Payment\v1\Message::initOnce();
        parent::__construct($data);
    }

    /**
     * Generated from protobuf field <code>string description = 1;</code>
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Generated from protobuf field <code>string description = 1;</code>
     * @param string $var
     * @return $this
     */
    public function setDescription($var)
    {
        GPBUtil::checkString($var, True);
        $this->description = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>string email = 2;</code>
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Generated from protobuf field <code>string email = 2;</code>
     * @param string $var
     * @return $this
     */
    public function setEmail($var)
    {
        GPBUtil::checkString($var, True);
        $this->email = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>.payment.v1.dto.Money amount = 3;</code>
     * @return \GRPC\Services\Payment\v1\Money|null
     */
    public function getAmount()
    {
        return isset($this->amount) ? $this->amount : null;
    }

    public function hasAmount()
    {
        return isset($this->amount);
    }

    public function clearAmount()
    {
        unset($this->amount);
    }

    /**
     * Generated from protobuf field <code>.payment.v1.dto.Money amount = 3;</code>
     * @param \GRPC\Services\Payment\v1\Money $var
     * @return $this
     */
    public function setAmount($var)
    {
        GPBUtil::checkMessage($var, Money::class);
        $this->amount = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>string payment_method = 4;</code>
     * @return string
     */
    public function getPaymentMethod()
    {
        return $this->payment_method;
    }

    /**
     * Generated from protobuf field <code>string payment_method = 4;</code>
     * @param string $var
     * @return $this
     */
    public function setPaymentMethod($var)
    {
        GPBUtil::checkString($var, True);
        $this->payment_method = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>.google.protobuf.Timestamp created_at = 5;</code>
     * @return \Google\Protobuf\Timestamp|null
     */
    public function getCreatedAt()
    {
        return isset($this->created_at) ? $this->created_at : null;
    }

    public function hasCreatedAt()
    {
        return isset($this->created_at);
    }

    public function clearCreatedAt()
    {
        unset($this->created_at);
    }

    /**
     * Generated from protobuf field <code>.google.protobuf.Timestamp created_at = 5;</code>
     * @param \Google\Protobuf\Timestamp $var
     * @return $this
     */
    public function setCreatedAt($var)
    {
        GPBUtil::checkMessage($var, \Google\Protobuf\Timestamp::class);
        $this->created_at = $var;

        return $this;
    }
}
