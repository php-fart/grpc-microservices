<?php

declare(strict_types=1);

namespace Generator\Generators\Command;

use Generator\Generators\Command\Annotation\DefaultValue;
use Generator\Generators\Command\Annotation\DocType;
use Generator\Generators\Command\Annotation\Optional;
use Generator\Generators\Command\Annotation\Type;
use Generator\PHP\Property\BuiltInType;
use Generator\PHP\Property\ClassType;
use Generator\PHP\Property\DateTime;
use Generator\PHP\Property\Timestamp;
use Generator\PHP\Property\EnumClassType;
use Generator\PHP\Property\RepeatableType;
use Generator\PHP\Property\TypeFactory;
use Google\Protobuf\Internal\EnumDescriptor;
use Google\Protobuf\Internal\FieldDescriptor;
use Internal\Shared\gRPC\Google\Type\Fields;
use Nette\PhpGenerator\Literal;

final class PropertyType
{
    /**
     * @param non-empty-string $variable
     * @param \Generator\PHP\Property\Type[] $types
     * @param string|null $comment
     */
    public function __construct(
        public readonly string $variable,
        public readonly array $types,
        public readonly ?string $comment = null,
        public array $annotations = [],
        public readonly array $attributes = [],
        public readonly ?FieldDescriptor $descriptor = null,
    ) {
    }

    public function getCamelCaseVariable(): string
    {
        return lcfirst(
            \implode(
                '',
                \array_map(
                    static fn(string $part) => \ucwords(\ctype_upper($part) ? \strtolower($part) : $part),
                    \explode('_', $this->variable),
                ),
            ),
        );
    }

    public function getMethodName(): string
    {
        return \sprintf('get%s', \ucfirst($this->getCamelCaseVariable()));
    }

    public function hasType(string $type): bool
    {
        foreach ($this->getPropertyTypes() as $propertyType) {
            if ($propertyType->isEqual($type)) {
                return true;
            }
        }

        return false;
    }

    public function isRepeatable(): bool
    {
        return $this->hasType('array');
    }

    public function isRepeatableByInitialType(): bool
    {
        foreach ($this->types as $type) {
            if ($type instanceof RepeatableType) {
                return true;
            }
        }

        return false;
    }

    public function getPropertyType(): string
    {
        return \implode('|', $this->getPropertyTypes());
    }

    /**
     * @return array<\Generator\PHP\Property\Type>
     */
    public function getPropertyTypes(): array
    {
        $types = [];
        $repeatableType = null;
        foreach ($this->types as $type) {
            if ($type instanceof RepeatableType) {
                $repeatableType = $type;
            }
        }

        if ($this->isEnumType()) {
            $type = new EnumClassType(
                $this->getEnumDescriptor()->getClass(),
            );

            if ($repeatableType !== null) {
                $types[] = new RepeatableType($type);
            } else {
                return [$type];
            }
        }

        if ($this->hasCustomType()) {
            $types = [...$types, ...$this->getCustomType()];
        } elseif ($repeatableType === null || !$this->isEnumType()) {
            $types = [...$types, ...$this->types];
        }

        if ($this->isOptional() || $this->isFieldMask()) {
            $types[] = new BuiltInType('null');
        }

        return $this->uniqueTypes($types);
    }

    public function getPropertyDocTypes(): array
    {
        $types = [];

        if ($this->isOptional()) {
            $types[] = new BuiltInType('null');
        }

        if ($this->hasCustomDocType()) {
            return $this->uniqueTypes([...$types, ...$this->getCustomDocType()]);
        }

        $types = [...$types, ...$this->getPropertyTypes()];

        return $this->uniqueTypes($types);
    }

    /**
     * @return array<\Generator\PHP\Property\Type>
     */
    private function uniqueTypes(array $types): array
    {
        $uniqueTypes = [];

        foreach ($types as $type) {
            $uniqueTypes[(string)$type] = $type;
        }

        return \array_values($uniqueTypes);
    }

    public function getCameCaseVariable(): string
    {
        return \lcfirst(\str_replace('_', '', \ucwords($this->variable, '_')));
    }

    public function isGoogleTimestampType(): bool
    {
        foreach ($this->getPropertyTypes() as $type) {
            if ($type instanceof Timestamp) {
                return true;
            }
        }

        return false;
    }

    public function isDateTimeType(): bool
    {
        foreach ($this->getPropertyTypes() as $type) {
            if ($type instanceof DateTime) {
                return true;
            }
        }

        return false;
    }

    public function isEnumType(): bool
    {
        return $this->getEnumDescriptor()?->getClass() !== null;
    }

    public function getEnumDescriptor(): ?EnumDescriptor
    {
        return $this->descriptor?->getEnumType();
    }

    public function isOptional(): bool
    {
        foreach ($this->annotations as $attribute) {
            if ($attribute instanceof Optional) {
                return true;
            }
        }

        return false;
    }

    public function hasDefaultValue(): bool
    {
        if ($this->isOptional() || $this->isFieldMask()) {
            return true;
        }

        if ($this->hasType('int') || $this->hasType('float') || $this->hasType('bool') || $this->hasType(
                'string',
            ) || $this->isRepeatable()) {
            return true;
        }

        foreach ($this->annotations as $attribute) {
            if ($attribute instanceof DefaultValue) {
                return true;
            }
        }

        return false;
    }

    public function getDefaultValue(): string|null|bool|int|float|array|Literal
    {
        foreach ($this->annotations as $attribute) {
            if ($attribute instanceof DefaultValue) {
                return $attribute->value;
            }
        }

        if ($this->hasType('string')) {
            return '';
        }

        if ($this->hasType('int')) {
            return 0;
        }

        if ($this->hasType('float')) {
            return 0.0;
        }

        if ($this->hasType('bool')) {
            return false;
        }

        if ($this->isRepeatable()) {
            return [];
        }

        return null;
    }

    public function isStringType(): bool
    {
        return $this->hasType('string') || $this->hasType(\Stringable::class);
    }

    public function hasCustomType(): bool
    {
        foreach ($this->annotations as $attribute) {
            if ($attribute instanceof Type) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return array<\Generator\PHP\Property\Type>
     */
    public function getCustomType(): array
    {
        $types = [];
        foreach ($this->annotations as $attribute) {
            if ($attribute instanceof Type) {
                $types = [...$types, ...\explode('|', $attribute->type)];
            }
        }

        return (new TypeFactory())->createMany(\implode('|', $types));
    }

    public function hasCustomDocType(): bool
    {
        foreach ($this->annotations as $attribute) {
            if ($attribute instanceof DocType) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return array<\Generator\PHP\Property\Type>
     */
    public function getCustomDocType(): array
    {
        $types = [];
        foreach ($this->annotations as $attribute) {
            if ($attribute instanceof DocType) {
                $types = [...$types, ...\explode('|', $attribute->type)];
            }
        }

        return (new TypeFactory())->createMany(\implode('|', $types));
    }

    /**
     * @return array<ClassType>
     */
    public function getClassTypes(): array
    {
        $types = [];

        foreach ($this->getPropertyTypes() as $type) {
            if ($type instanceof ClassType) {
                $types[] = $type;
            }
        }

        return $types;
    }

    public function setDefaultValue(mixed $value): void
    {
        $this->annotations[] = new DefaultValue($value);
    }

    private function isFieldMask(): bool
    {
        foreach ($this->types as $type) {
            if ($type instanceof ClassType && $type->getName() === Fields::class) {
                return true;
            }
        }

        return false;
    }
}
