<?php

declare(strict_types=1);

namespace Generator\Generators\Message;

use Doctrine\Common\Annotations\Annotation\Target;
use Doctrine\Common\Annotations\DocParser;
use Generator\Generators\Command\PropertyType;
use Generator\PHP\AnnotationsParser;
use Generator\PHP\AttributesParser;
use Generator\PHP\Property\Type;
use Generator\PHP\Property\TypeFactory;
use Google\Protobuf\Internal\Descriptor;

final readonly class MessageCommentsParser
{
    private const PATTERN = '/@type (?P<types>[\w\[\]|\\\\\<\>]+) \$(?P<variable>\w+)(\n\s+\*\s+(?P<comment>[\w\s\/]*))?/';

    public function __construct(
        private TypeFactory $typeFactory,
        private AttributesParser $attributesParser = new AttributesParser(),
    ) {
    }

    /**
     * @return PropertyType[]
     */
    public function parse(?Descriptor  $descriptor, \ReflectionMethod $method): iterable
    {
        $docblock = $method->getDocComment();
        \preg_match_all(self::PATTERN, $docblock, $matches, PREG_SET_ORDER);

        $types = [];

        foreach ($matches as $match) {
            $property = $method->getDeclaringClass()->getProperty($match['variable']);
            $propertyDocs = AnnotationsParser::fixDocComment($property->getDocComment());
            $parser = new DocParser();
            $parser->setTarget(Target::TARGET_PROPERTY);

            $propertyTypes = \array_map(
                fn(string $type): ?Type => $this->typeFactory->create($type),
                \explode('|', $match['types']),
            );

            $types[] = new PropertyType(
                variable: \trim($match['variable']),
                types: \array_filter($propertyTypes),
                comment: !empty($match['comment']) ? trim($match['comment']) : null,
                annotations: $parser->parse(
                    \implode("\n", \array_map(fn($line) => '* ' . $line, \explode("\n", $propertyDocs))),
                ),
                attributes: $this->attributesParser->parse($propertyDocs),
                descriptor: $descriptor?->getFieldByName(\trim($match['variable'])),
            );
        }

        return $types;
    }
}
