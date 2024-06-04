<?php

declare(strict_types=1);

namespace Generator\PHP\Property;

final readonly class RepeatableType extends Type
{
    public Type $iterableType;

    public function __construct(Type $type)
    {
        $this->iterableType = $type;
        parent::__construct('array', $type->type . '[]');
    }
}
