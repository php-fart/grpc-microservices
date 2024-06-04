<?php

declare(strict_types=1);

namespace App\Endpoint\GRPC\Mapper;

final class TimestampMapper
{
    public function toMessage(?\DateTimeInterface $dateTime): ?\Google\Protobuf\Timestamp
    {
        if ($dateTime === null) {
            return null;
        }

        $timestamp = new \Google\Protobuf\Timestamp();
        $timestamp->fromDateTime(\DateTime::createFromInterface($dateTime));

        return $timestamp;
    }
}
