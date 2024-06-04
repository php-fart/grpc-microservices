<?php

declare(strict_types=1);

namespace Generator\PHP;

use Spiral\Files\FilesInterface;
use Spiral\Reactor\FileDeclaration;

final readonly class ClassDeclarationFactory
{
    public function __construct(
        private FilesInterface $files,
    ) {}

    public function createFromFile(string $filePath): ClassDeclaration
    {
        if (!$this->files->exists($filePath)) {
            throw new \InvalidArgumentException(\sprintf('File %s not exists', $filePath));
        }

        $content = $this->files->read($filePath);

        $fileDeclaration = FileDeclaration::fromCode($content);

        $namespaceDeclaration = $fileDeclaration->getNamespaces()->getIterator()->current();
        $classDeclaration = $namespaceDeclaration->getClasses()->getIterator()->current();

        if (!$classDeclaration) {
            $classDeclaration = $namespaceDeclaration->getInterfaces()->getIterator()->current();
                $classDeclaration ?? throw new \InvalidArgumentException(\sprintf('Class %s not exists', $filePath));
        }

        return new ClassDeclaration(
            files: $this->files,
            file: $fileDeclaration,
            namespace: $namespaceDeclaration,
            class: $classDeclaration,
            filePath: $filePath,
        );
    }

    public function createFromExistsClass(string $class): ClassDeclaration
    {
        if (!\class_exists($class)) {
            throw new \InvalidArgumentException(\sprintf('Class %s not exists', $class));
        }

        $refl = new \ReflectionClass($class);

        return $this->createFromFile($refl->getFileName());
    }

    public function createFromClass(string $class, string $targetPath = ''): ClassDeclaration
    {
        $transformer = new ClassTransformer($class);

        $file = new FileDeclaration();
        $namespace = $transformer->getNamespace();
        $namespaceDeclaration = $file->addNamespace($namespace);
        $class = $namespaceDeclaration->addClass($transformer->getShortName());

        $targetLastSegment = \array_filter(\explode('/', $targetPath));
        $targetLastSegment = \array_pop($targetLastSegment);

        $path = $transformer->cleanNamespace()->getFilePath();

        if (!empty($targetLastSegment) && ($pos = \strpos($path, $targetLastSegment . '/')) !== false) {
            $path = \substr($path, $pos + \strlen($targetLastSegment) + 1);
        }

        $filePath = \rtrim($targetPath, '/') . '/' . $path;

        return new ClassDeclaration(
            files: $this->files,
            file: $file,
            namespace: $namespaceDeclaration,
            class: $class,
            filePath: $filePath,
        );
    }
}
