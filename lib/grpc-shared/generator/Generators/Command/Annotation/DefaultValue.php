<?php

declare(strict_types=1);

namespace Generator\Generators\Command\Annotation;

use Nette\PhpGenerator\Literal;

/**
 * @Annotation
 * @NamedArgumentConstructor
 */
final readonly class DefaultValue
{
    public function __construct(
        public string|null|bool|int|Literal $value
    ) {

    }
}
