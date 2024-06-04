<?php

namespace GRPC\Services\Payment\v1;

use Internal\Shared\gRPC\Request\RequestContext;
use Spiral\RoadRunner\GRPC;

interface PaymentServiceInterface extends GRPC\ServiceInterface
{
    public const NAME = 'payment.v1.PaymentService';

    /**
     * @param RequestContext $ctx
     */
    public function Charge(GRPC\ContextInterface $ctx, ChargeRequest $in): ChargeResponse;
}
