<?php

declare(strict_types=1);

namespace Generator\PHP;

final readonly class AttributesParser
{
    private const CLASS_MAP = [
        'SecurityAssert\\' => 'Symfony\Component\Security\Core\Validator\Constraints\\',
        'Assert\\' => 'Symfony\Component\Validator\Constraints\\',
    ];

    public function parse(string $dockblock): array
    {
        $attributes = [];

        // Find all occurrences of #[...] constructions
        \preg_match_all('/#\[([^\]]+)\]/', $dockblock, $matches);

        // Extract the matched attributes
        if (isset($matches[0])) {
            foreach ($matches[0] as $attribute) {
                // Remove newlines and extra spaces within the annotation
                $attribute = \preg_replace('/\s+/', ' ', $attribute);

                if (\preg_match('/\#\[(?<class>[A-Za-z\\\]+)(\(\)){0,1}]/', $attribute, $classMatches) === 1) {
                    $className = \str_replace(
                        \array_keys(self::CLASS_MAP),
                        \array_values(self::CLASS_MAP),
                        $classMatches['class']
                    );

                    $attributes[] = [
                        'class' => $className,
                        'arguments' => []
                    ];
                    continue;
                }

                $classMatches = [];
                // Extract the class name and attribute values
                \preg_match('/\#\[(?<class>[A-Za-z\\\]+)\(/', $attribute, $classMatches);
                $className = \str_replace(
                    \array_keys(self::CLASS_MAP),
                    \array_values(self::CLASS_MAP),
                    $classMatches['class']
                );

                \preg_match_all('/(\w+):\s*(.*?)(?=(,\s*\w+:)|\s*\))/s', $attribute, $attrMatches);
                $attributePairs = \array_combine($attrMatches[1], $attrMatches[2]);

                $arguments = [];
                foreach ($attributePairs as $key => $value) {
                    $arguments[$key] = trim($value, '"\',');
                }

                // Create the attribute array
                $attributes[] = [
                    'class' => $className,
                    'arguments' => $arguments
                ];
            }
        }

        return $attributes;
    }
}
