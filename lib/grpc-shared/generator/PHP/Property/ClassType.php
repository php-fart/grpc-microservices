<?php

declare(strict_types=1);

namespace Generator\PHP\Property;

use Generator\PHP\ClassTransformer;

readonly class ClassType extends Type
{
    private ClassTransformer $transformer;

    /**
     * @param class-string $type
     */
    public function __construct(string $type)
    {
        parent::__construct($type);
        $this->transformer = new ClassTransformer($type);
    }

    public function getShortName(): string
    {
        return $this->transformer->getShortName();
    }

    public function getName(): string
    {
        return $this->transformer->class;
    }

    public function getNamespace(): string
    {
        return $this->transformer->getNamespace();
    }
}
