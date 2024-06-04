<?php

declare(strict_types=1);

namespace Generator\Generators\Command\Annotation;

/**
 * @Annotation
 * @NamedArgumentConstructor
 */
final readonly class Event
{
    public function __construct(
        public string $description,
        public ?string $topic = null,
    ) {
    }
}
