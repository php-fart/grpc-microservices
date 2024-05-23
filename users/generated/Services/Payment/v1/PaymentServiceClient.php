<?php

declare(strict_types=1);

namespace GRPC\Services\Payment\v1;

use Spiral\Core\InterceptableCore;
use Spiral\RoadRunner\GRPC\ContextInterface;

class PaymentServiceClient implements PaymentServiceInterface
{
    public function __construct(
        private readonly InterceptableCore $core,
    ) {
    }

    public function Charge(ContextInterface $ctx, ChargeRequest $in): ChargeResponse
    {
        [$response, $status] = $this->core->callAction(PaymentServiceInterface::class, '/'.self::NAME.'/Charge', [
            'in' => $in,
            'ctx' => $ctx,
            'responseClass' => \GRPC\Services\Payment\v1\ChargeResponse::class,
        ]);

        return $response;
    }
}
