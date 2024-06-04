<?php

declare(strict_types=1);

namespace Generator\Generators\Command;

use Generator\PHP\ClassDeclaration;
use Generator\PHP\ClassTransformer;

final class JsonSerializationGenerator
{
    public function generate(ClassDeclaration $class, array $properties): void
    {
        $class->class->addImplement(\JsonSerializable::class);

        $serializeMethod = $class->class->addMethod('jsonSerialize');
        $serializeMethod->setReturnType('array');

        if ($properties === []) {
            $serializeMethod->addBody('return [];');
            return;
        }

        $serializeMethod->addBody('$data = [];');

        foreach ($properties as $property) {
            $serializeMethod->addBody(
                '$data[\'' . $property->variable . '\'] = ' . $this->getJsonSerializeVariable($property) . ';',
            );
        }

        $serializeMethod->addBody('return $data;');
    }

    private function getJsonSerializeVariable(PropertyType $property): string
    {
        if ($property->isRepeatableByInitialType() && $property->isEnumType()) {
            $typeClass = new ClassTransformer($property->getEnumDescriptor()->getClass());

            return \sprintf(
                '\array_map(static fn(%s $item): int => $item->value, $this->%s)',
                $typeClass->getShortName(),
                $property->getCameCaseVariable(),
            );
        }

        if ($property->isStringType()) {
            if ($property->hasType('int') || $property->hasType('null')) {
                return \str_replace(
                    '%s',
                    '$this->' . $property->getCameCaseVariable(),
                    <<<PHP
match (true) {
    \is_null(%s),
    \is_int(%s) => %s,
    default => (string) %s,
}
PHP,
                );
            }

            return '(string) $this->' . $property->getCameCaseVariable();
        }

        if ($property->isEnumType()) {
            return '$this->' . $property->getCameCaseVariable() . '->value';
        }

        if ($property->isGoogleTimestampType()) {
            return '$this->' . $property->getCameCaseVariable() . "?->format('Y-m-d\TH:i:s.u\Z')";
        }

        if ($property->isDateTimeType()) {
            return '$this->' . $property->getCameCaseVariable() . '?->format(\DateTimeInterface::RFC3339)';
        }

        return '$this->' . $property->getCameCaseVariable();
    }
}
