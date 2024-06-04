<?php

declare(strict_types=1);

namespace Internal\Shared\gRPC\Request;

use Spiral\RoadRunner\GRPC\ContextInterface;

final readonly class RequestContext implements ContextInterface
{
    public static function create(array $values = []): RequestContext
    {
        return new self(new \Spiral\RoadRunner\GRPC\Context($values));
    }

    public function __construct(
        private ContextInterface $context,
    ) {}

    public function withAuthToken(string $token): ContextInterface
    {
        return $this->withMetadata('x-auth-token', [$token]);
    }

    public function getAuthToken(): ?string
    {
        $token = $this->getMetadata('x-auth-token') ?? null;

        if ($token !== null) {
            return $token[0];
        }

        return null;
    }

    public function withTelemetryContext(?array $context): ContextInterface
    {
        return $this->withMetadata('telemetry', [
            \json_encode($context),
        ]);
    }

    public function getTelemetry(): array
    {
        $context = $this->getMetadata('telemetry') ?? [];

        if ($context !== null && isset($context[0])) {
            return \json_decode($context[0], true);
        }

        return [];
    }

    public function withValue(string $key, mixed $value): ContextInterface
    {
        return new self($this->context->withValue($key, $value));
    }

    public function getValue(string $key): mixed
    {
        return $this->context->getValue($key);
    }

    public function getValues(): array
    {
        return $this->context->getValues();
    }

    private function getMetadata(string $key): mixed
    {
        return $this->context->getValue('metadata', [])[$key]
            ?? $this->context->getValue($key)
            ?? null;
    }

    private function withMetadata(string $key, array $value): ContextInterface
    {
        $metadata = $this->getMetadata($key);
        $metadata[$key] = $value;

        return new self($this->context->withValue('metadata', $metadata));
    }
}