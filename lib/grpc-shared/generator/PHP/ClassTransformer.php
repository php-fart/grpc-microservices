<?php

declare(strict_types=1);

namespace Generator\PHP;

final readonly class ClassTransformer implements \Stringable
{
    public function __construct(
        public string $class,
    ) {
    }

    public function getNamespace(): string
    {
        $segments = \explode('\\', $this->class);

        return \implode('\\', \array_slice($segments, 0, -1));
    }

    public function getShortName(): string
    {
        $segments = \explode('\\', $this->class);

        return \array_pop($segments);
    }

    public function cleanNamespace(?string $prefix = null): self
    {
        $namespace = $this->getNamespace();

        if (\str_starts_with($namespace, 'Internal\\Shared\\gRPC\\Services\\')) {
            $namespace = \str_replace('Internal\\Shared\\gRPC\\Services\\', '', $namespace);

            if ($prefix) {
                $prefix = \explode('\\', $prefix);
                $namespace = \explode('\\', $namespace);
                $namespace = \implode('\\', \array_filter([...$prefix, ...$namespace]));
            }
        }

        return new self($namespace . '\\' . $this->getShortName());
    }

    public function getDirectoryPath(): string
    {
        return \implode('/', \explode('\\', $this->getNamespace()));
    }

    public function getFilePath(?string $suffix = null): string
    {
        $filename = $this->getShortName();
        if ($suffix) {
            $filename .= \ucfirst($suffix);
        }

        return $this->getDirectoryPath() . '/' . $filename . '.php';
    }

    public function __toString()
    {
        return $this->class;
    }
}
