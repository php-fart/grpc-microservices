<?php

declare(strict_types=1);

namespace Generator\PHP\Property;

abstract readonly class Type implements \Stringable
{
    public string $docType;

    public function __construct(
        public string $type,
        string $doctype = null,
    ) {
        $this->docType = $doctype ?? $type;
    }

    public function isEqual(string $type): bool
    {
        return $this->type === $type;
    }

    public function __toString(): string
    {
        return $this->type;
    }
}
