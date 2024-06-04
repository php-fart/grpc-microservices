<?php

declare(strict_types=1);

namespace Generator\PHP;

use Spiral\Files\FilesInterface;
use Spiral\Reactor\FileDeclaration;
use Spiral\Reactor\Partial\PhpNamespace;
use Spiral\Reactor\Writer;

final readonly class ClassDeclaration implements \Stringable
{
    public function __construct(
        public FilesInterface $files,
        public FileDeclaration $file,
        public PhpNamespace $namespace,
        public \Spiral\Reactor\ClassDeclaration|\Spiral\Reactor\InterfaceDeclaration|\Spiral\Reactor\EnumDeclaration $class,
        public string $filePath,
    ) {
    }

    public function getName(): string
    {
        return $this->class->getName();
    }

    public function markAsFinal(): void
    {
        $this->class->setFinal(true);
    }

    public function markAsReadonly(): void
    {
        $this->class->getElement()->setReadonly(true);
    }

    public function getNameWithNamespace(): string
    {
        return $this->getNamespace() . '\\' . $this->getName();
    }

    public function getNamespace(): string
    {
        return $this->namespace->getName();
    }

    public function isClassNameEndsWith(string $suffix): bool
    {
        return \str_ends_with($this->getName(), $suffix);
    }

    public function persist(): void
    {
        (new Writer($this->files))->write($this->filePath, $this->file);
    }

    public function getReflection(): \ReflectionClass
    {
        return new \ReflectionClass($this->getNameWithNamespace());
    }

    public function addImplement(string $interface): void
    {
        $this->class->addImplement($interface);
    }

    public function __toString()
    {
        return $this->filePath;
    }
}
