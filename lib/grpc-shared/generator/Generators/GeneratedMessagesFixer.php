<?php

declare(strict_types=1);

namespace Generator\Generators;

use Generator\PHP\AnnotationsParser;
use Generator\PHP\ClassDeclarationFactory;
use Google\Protobuf\Internal\Message;
use Spiral\Files\FilesInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class GeneratedMessagesFixer implements GeneratorInterface
{
    public function __construct(
        private readonly FilesInterface $files,
        private readonly OutputInterface $output,
        private readonly AnnotationsParser $annotationsParser = new AnnotationsParser(),
    ) {
    }

    public function run(array $files, string $targetPath, string $commandNamespace): void
    {
        $factory = new ClassDeclarationFactory($this->files);

        foreach ($files as $file) {
            try {
                $class = $factory->createFromFile($file);
            } catch (\Throwable $e) {
                $this->output->writeln('<error>Failed to parse file "' . $file . '"</error>');
                continue;
            }

            if (!$class->getReflection()->isSubclassOf(Message::class)) {
                continue;
            }

            $refl = $class->getReflection();
            $docblock = $refl->getDocComment();
            if (!empty($docblock)) {
                $annotations = $this->annotationsParser->parseFromClass($refl);
                $class->class->setComment(AnnotationsParser::fixDocComment($docblock));
            }

            foreach ($refl->getProperties() as $property) {
                $docblock = $property->getDocComment();
                if (empty($docblock)) {
                    continue;
                }

                $docblock = AnnotationsParser::fixDocComment($docblock);
                try {
                    $p = $class->class->getProperty($property->getName());
                    $p->setComment($docblock);
                } catch (\Throwable $e) {
                    if ($this->output->isVerbose()) {
                        $this->output->writeln(
                            '<error>Failed to add attribute to property "' . $property->getName() . '"</error>',
                        );
                    }
                }
            }


            $class->persist();
        }
    }
}
