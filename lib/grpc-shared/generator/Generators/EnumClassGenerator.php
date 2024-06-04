<?php

declare(strict_types=1);

namespace Generator\Generators;

use Generator\PHP\ClassDeclaration;
use Generator\PHP\ClassTransformer;
use Google\Protobuf\Internal\EnumDescriptor;
use Spiral\Files\FilesInterface;
use Spiral\Reactor\FileDeclaration;

final readonly class EnumClassGenerator
{
    private const COMMAND_NAMESPACE = 'Internal\\Shared\\gRPC\\Services\\';

    public function __construct(
        private FilesInterface $files,
        private string $targetPath,
        private string $commandNamespace,
    ) {
    }

    public function generate(EnumDescriptor $descriptor): string
    {
        $transformer = new ClassTransformer($descriptor->getClass());

        $file = new FileDeclaration();
        $namespace = $transformer->cleanNamespace($this->commandNamespace)->getNamespace();
        $namespaceDeclaration = $file->addNamespace($namespace);

        $enumDeclaration = $namespaceDeclaration->addEnum($transformer->getShortName());
        $enumDeclaration->setType('int');

        $targetLastSegment = \array_filter(\explode('/', $this->targetPath));
        $targetLastSegment = \array_pop($targetLastSegment);

        $path = $transformer->cleanNamespace('Command')->getFilePath();

        if (!empty($targetLastSegment) && ($pos = \strpos($path, $targetLastSegment . '/')) !== false) {
            $path = \substr($path, $pos + \strlen($targetLastSegment) + 1);
        }

        $filePath = \rtrim($this->targetPath, '/') . '/' . $path;

        $declaration = new ClassDeclaration(
            files: $this->files,
            file: $file,
            namespace: $namespaceDeclaration,
            class: $enumDeclaration,
            filePath: $filePath,
        );

        $default = null;

        for ($i = 0; $i < $descriptor->getValueCount(); $i++) {
            /** @var \Google\Protobuf\EnumValueDescriptor $value */
            $value = $descriptor->getValueDescriptorByIndex($i);
            $key = \ucfirst(\strtolower($value->getName()));
            // TODO: use this instead of the above to generate the correct enum names
//            $key = \implode('', \array_map(
//                static fn(string $part) => \ucwords(\ctype_upper($part) ? \strtolower($part) : $part),
//                \explode('_', $value->getName())
//            ));


            if ($i === 0) {
                $default = $transformer->getShortName() . '::' . $key;
            }

            $enumDeclaration->addCase($key, $value->getNumber());
        }

        $declaration->class->addComment('This class is read only. Please do not edit it directly.');
        $declaration->persist();

        return $default;
    }
}
