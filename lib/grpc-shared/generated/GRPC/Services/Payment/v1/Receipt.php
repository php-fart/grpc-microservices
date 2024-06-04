<?php

namespace GRPC\Services\Payment\v1;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\GPBUtil;
use Google\Protobuf\Internal\RepeatedField;

/**
 * Generated from protobuf message <code>payment.v1.dto.Receipt</code>
 */
class Receipt extends \Google\Protobuf\Internal\Message
{
    /** Generated from protobuf field <code>string id = 1;</code> */
    protected $id = '';

    /** Generated from protobuf field <code>string transaction_id = 2;</code> */
    protected $transaction_id = '';

    /** Generated from protobuf field <code>.payment.v1.dto.Money amount = 3;</code> */
    protected $amount = null;

    /** Generated from protobuf field <code>.payment.v1.dto.Money tax = 4;</code> */
    protected $tax = null;

    /** Generated from protobuf field <code>.google.protobuf.Timestamp paid_at = 5;</code> */
    protected $paid_at = null;

    /**
     * Constructor.
     *
     * @param array $data {
     *     Optional. Data for populating the Message object.
     *
     *     @type string $id
     *     @type string $transaction_id
     *     @type \GRPC\Services\Payment\v1\Money $amount
     *     @type \GRPC\Services\Payment\v1\Money $tax
     *     @type \Google\Protobuf\Timestamp $paid_at
     * }
     */
    public function __construct($data = null)
    {
        \GRPC\ProtobufMetadata\Payment\v1\Message::initOnce();
        parent::__construct($data);
    }

    /**
     * Generated from protobuf field <code>string id = 1;</code>
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Generated from protobuf field <code>string id = 1;</code>
     * @param string $var
     * @return $this
     */
    public function setId($var)
    {
        GPBUtil::checkString($var, True);
        $this->id = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>string transaction_id = 2;</code>
     * @return string
     */
    public function getTransactionId()
    {
        return $this->transaction_id;
    }

    /**
     * Generated from protobuf field <code>string transaction_id = 2;</code>
     * @param string $var
     * @return $this
     */
    public function setTransactionId($var)
    {
        GPBUtil::checkString($var, True);
        $this->transaction_id = $var;

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
     * Generated from protobuf field <code>.payment.v1.dto.Money tax = 4;</code>
     * @return \GRPC\Services\Payment\v1\Money|null
     */
    public function getTax()
    {
        return isset($this->tax) ? $this->tax : null;
    }

    public function hasTax()
    {
        return isset($this->tax);
    }

    public function clearTax()
    {
        unset($this->tax);
    }

    /**
     * Generated from protobuf field <code>.payment.v1.dto.Money tax = 4;</code>
     * @param \GRPC\Services\Payment\v1\Money $var
     * @return $this
     */
    public function setTax($var)
    {
        GPBUtil::checkMessage($var, Money::class);
        $this->tax = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>.google.protobuf.Timestamp paid_at = 5;</code>
     * @return \Google\Protobuf\Timestamp|null
     */
    public function getPaidAt()
    {
        return isset($this->paid_at) ? $this->paid_at : null;
    }

    public function hasPaidAt()
    {
        return isset($this->paid_at);
    }

    public function clearPaidAt()
    {
        unset($this->paid_at);
    }

    /**
     * Generated from protobuf field <code>.google.protobuf.Timestamp paid_at = 5;</code>
     * @param \Google\Protobuf\Timestamp $var
     * @return $this
     */
    public function setPaidAt($var)
    {
        GPBUtil::checkMessage($var, \Google\Protobuf\Timestamp::class);
        $this->paid_at = $var;

        return $this;
    }
}
