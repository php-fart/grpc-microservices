<?php

declare(strict_types=1);

namespace Generator\Generators;

interface GeneratorInterface
{
    /**
     * @param non-empty-string[] $files
     * @param non-empty-string $targetPath
     * @param non-empty-string $namespace
     */
    public function run(array $files, string $targetPath, string $namespace): void;
}
