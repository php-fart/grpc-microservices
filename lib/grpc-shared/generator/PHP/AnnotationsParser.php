<?php

declare(strict_types=1);

namespace Generator\PHP;

use Doctrine\Common\Annotations\Annotation\Target;
use Doctrine\Common\Annotations\AnnotationException;
use Doctrine\Common\Annotations\DocParser;

final readonly class AnnotationsParser
{
    public function __construct(
        private DocParser $docParser = new DocParser(),
    ) {
    }

    /**
     * @throws \ReflectionException
     * @throws AnnotationException
     */
    public function parseFromClass(\ReflectionClass $class): array
    {
        return $this->parse($class->getDocComment(), Target::TARGET_CLASS);
    }

    /**
     * @throws \ReflectionException
     * @throws AnnotationException
     */
    public function parseFromMethod(\ReflectionMethod $method): array
    {
        return $this->parse($method->getDocComment(), Target::TARGET_METHOD);
    }

    /**
     * @throws \ReflectionException
     * @throws AnnotationException
     */
    private function parse(string $docblock, int $target): array
    {
        $this->docParser->setTarget($target);

        $block = self::fixDocComment($docblock);
        $block = \implode("\n", \array_map(fn($line) => '* ' . $line, \explode("\n", $block)));

        return $this->docParser->parse($block);
    }

    public static function fixDocComment(string $docblock): string
    {
        // Remove the leading asterisks and spaces from each line
        $lines = \explode("\n", $docblock);
        $lines = \array_map(fn($line) => \ltrim($line, " \t*"), $lines);

        // Remove the first and last lines (the opening and closing comment markers)
        \array_shift($lines);
        \array_pop($lines);

        // Join the remaining lines into a single string
        $docblock = \implode("\n", $lines);

        $docblock = \str_replace('&#64;', '@Generator\\Generators\\Command\\Annotation\\', $docblock);
        $docblock = \str_replace('#[Assert', '#[Symfony\Component\Validator\Constraints', $docblock);

        return \str_replace(
            '#[SecurityAssert',
            '#[Symfony\Component\Security\Core\Validator\Constraints',
            $docblock,
        );
    }
}
