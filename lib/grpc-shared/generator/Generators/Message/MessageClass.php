<?php

declare(strict_types=1);

namespace Generator\Generators\Message;

use Generator\Generators\Command\Annotation;
use Generator\Generators\Command\PropertyType;
use Google\Protobuf\Internal\Message;

final readonly class MessageClass
{
    /**
     * @param class-string<Message> $class
     * @param PropertyType[] $properties
     */
    public function __construct(
        public string $class,
        public array $properties,
        public array $attributes,
    ) {
    }

    public function isGuarded(): bool
    {
        foreach ($this->attributes as $attribute) {
            if ($attribute instanceof Annotation\Guarded) {
                return true;
            }
        }

        return false;
    }
}
