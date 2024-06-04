<?php

declare(strict_types=1);

namespace Generator\Generators\Command\Annotation;

/**
 * @Annotation
 * @NamedArgumentConstructor
 */
final readonly class DocType
{
    public function __construct(
        public string $type
    ) {
    }
}
