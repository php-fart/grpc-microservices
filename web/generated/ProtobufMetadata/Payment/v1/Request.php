<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: payment/v1/request.proto

namespace GRPC\ProtobufMetadata\Payment\v1;

class Request
{
    public static $is_initialized = false;

    public static function initOnce() {
        $pool = \Google\Protobuf\Internal\DescriptorPool::getGeneratedPool();

        if (static::$is_initialized == true) {
          return;
        }
        \GRPC\ProtobufMetadata\Payment\v1\Message::initOnce();
        $pool->internalAddGeneratedFile(
            '
�
payment/v1/request.protopayment.v1.request"9

payment (2.payment.v1.dto.PaymentB>�GRPC\\Services\\Payment\\v1� GRPC\\ProtobufMetadata\\Payment\\v1bproto3'
        , true);

        static::$is_initialized = true;
    }
}
