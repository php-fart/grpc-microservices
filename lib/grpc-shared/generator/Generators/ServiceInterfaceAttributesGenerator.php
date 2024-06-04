<?php

declare(strict_types=1);

namespace Generator\Generators;

use Generator\PHP\AnnotationsParser;
use Generator\PHP\ClassDeclarationFactory;
use Internal\Shared\gRPC\Attribute\Guarded;
use Internal\Shared\gRPC\Attribute\Internal;
use Internal\Shared\gRPC\Request\RequestContext;
use Spiral\Files\FilesInterface;
use Generator\Generators\Command\Annotation;

final class ServiceInterfaceAttributesGenerator implements GeneratorInterface
{
    public function __construct(
        private readonly FilesInterface $files,
        private readonly AnnotationsParser $annotationsParser = new AnnotationsParser()
    ) {
    }

    public function run(array $files, string $targetPath, string $namespace): void
    {
        $classFactory = new ClassDeclarationFactory($this->files);
        foreach ($files as $file) {
            if (!\str_ends_with($file, 'Interface.php')) {
                continue;
            }

            $iterfaceClass = $classFactory->createFromFile($file);
            $iterfaceClass->namespace->addUse(RequestContext::class);

            foreach ($iterfaceClass->class->getMethods() as $method) {
                $method->setComment('');
                $method->addComment('@param RequestContext $ctx');

                foreach ($method->getParameters() as $parameter) {
                    if ($parameter->getName() !== 'in') {
                        continue;
                    }

                    $requestClass = new \ReflectionClass($parameter->getType());
                    $annotations = $this->annotationsParser->parseFromClass($requestClass);

                    foreach ($annotations as $annotation) {
                        if ($annotation instanceof Annotation\Guarded) {
                            $method->addAttribute(Guarded::class);
                            $iterfaceClass->namespace->addUse(Guarded::class);
                        }

                        if ($annotation instanceof Annotation\Internal) {
                            $method->addAttribute(Internal::class);
                            $iterfaceClass->namespace->addUse(Internal::class);
                        }
                    }
                }
            }

            $iterfaceClass->persist();
        }
    }
}
