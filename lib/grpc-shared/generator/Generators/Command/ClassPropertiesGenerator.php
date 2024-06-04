<?php

declare(strict_types=1);

namespace Generator\Generators\Command;

use Generator\Generators\EnumClassGenerator;
use Generator\PHP\ClassDeclaration;
use Generator\PHP\ClassTransformer;
use Generator\PHP\Property\ClassType;
use Generator\PHP\Property\EnumClassType;
use Generator\PHP\Property\RepeatableType;
use Generator\PHP\Property\Type;
use Nette\PhpGenerator\Literal;
use Spiral\Files\FilesInterface;

final readonly class ClassPropertiesGenerator
{
    public function __construct(
        private FilesInterface $files,
        private string $targetPath,
        private string $commandNamespace,
    ) {
    }

    /**
     * @param PropertyType[] $properties
     */
    public function generate(ClassDeclaration $class, array $properties): void
    {
        if ($properties === []) {
            return;
        }

        $method = $class->class->addMethod('__construct');

        foreach ($properties as $property) {
            \array_map(
                function (Type $type) use ($class, $property): Type {
                    if ($type instanceof EnumClassType) {
                        $property->setDefaultValue(
                            new Literal(
                                (new EnumClassGenerator($this->files, $this->targetPath, $this->commandNamespace))
                                    ->generate($property->getEnumDescriptor()),
                            ),
                        );
                    } elseif ($type instanceof RepeatableType && $type->iterableType instanceof EnumClassType) {
                        (new EnumClassGenerator($this->files, $this->targetPath, $this->commandNamespace))
                            ->generate($property->getEnumDescriptor());
                    }

                    return $type;
                },
                $property->getPropertyTypes(),
            );
        }

        \uasort($properties, fn(PropertyType $a, PropertyType $b) => $a->hasDefaultValue() <=> $b->hasDefaultValue());

        foreach ($properties as $property) {
            $types = $property->getPropertyTypes();
            $types = \array_map(
                function (Type $type) use ($class, $property): string {
                    if ($type instanceof ClassType) {
                        $typeClass = new ClassTransformer($type->getName());
                        $typeClass = $typeClass->cleanNamespace($this->commandNamespace);
                        $class->namespace->addUse($typeClass->class);

                        return $typeClass->class;
                    } elseif ($type instanceof RepeatableType && $type->iterableType instanceof ClassType) {
                        $typeClass = new ClassTransformer($type->iterableType->getName());
                        $class->namespace->addUse($typeClass->cleanNamespace($this->commandNamespace)->class);
                    }

                    return (string)$type;
                },
                $types,
            );

            if ($property->isStringType()) {
                $class->namespace->addUse(\Stringable::class);
                $types[] = \Stringable::class;
            }

            $type = \implode('|', $types);

            $p = $method->addPromotedParameter($property->getCameCaseVariable())
                ->setType($type);

            if ($property->hasDefaultValue()) {
                $p->setDefaultValue($property->getDefaultValue());
            } elseif (\in_array('bool', $types)) {
                $p->setDefaultValue(false);
            }


            foreach ($property->attributes as $attribute) {
                $p->addAttribute($attribute['class'], $attribute['arguments']);

                if (\str_starts_with($attribute['class'], 'Symfony\Component\Validator\Constraints')) {
                    $class->namespace->addUse('Symfony\Component\Validator\Constraints', 'Assert');
                } else {
                    $class->namespace->addUse($attribute['class']);
                }
            }

            $types = \array_map(
                function (Type $type) use ($class): ?string {
                    if ($type instanceof RepeatableType) {
                        $docType = $type->iterableType;

                        if ($docType instanceof ClassType) {
                            $docTypeClass = new ClassTransformer($docType->getName());
                            $docTypeClass = $docTypeClass->cleanNamespace($this->commandNamespace);
                            $class->namespace->addUse($docTypeClass->class);

                            return $docTypeClass->getShortName() . '[]';
                        }

                        return $docType . '[]';
                    }

                    if ($type->isEqual('null')) {
                        return $type->docType;
                    }

                    return null;
                },
                $property->getPropertyDocTypes(),
            );

            $types = \array_filter($types);

            if ($types === [] || $types === ['null']) {
                continue;
            }

            $docComment = \implode('|', $types);

            $p->setComment('@var ' . $docComment . ' $' . $property->getCameCaseVariable());
        }
    }
}
