<?php

declare(strict_types=1);

namespace Generator\Console;

use Generator\CommandExecutor;
use Generator\Exception\CompileException;
use Generator\Generators\GeneratedMessagesFixer;
use Generator\Generators\GeneratorInterface;
use Generator\Generators\ServiceInterfaceAttributesGenerator;
use Generator\ProtocCommandBuilder;
use Generator\ProtoCompiler;
use Spiral\Files\Files;
use Spiral\Files\FilesInterface;
use Spiral\RoadRunnerBridge\GRPC\Generator\BootloaderGenerator;
use Spiral\RoadRunnerBridge\GRPC\Generator\ConfigGenerator;
use Spiral\RoadRunnerBridge\GRPC\Generator\ServiceClientGenerator;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'generate')]
final class GeneratorCommand extends Command
{
    /**
     * @param non-empty-string $rootDir
     * @param string[] $protoFileDirs
     */
    public function __construct(
        private readonly FilesInterface $files,
        private readonly string $rootDir,
        private readonly array $protoFileDirs,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $binaryPath = $this->rootDir . '/bin/protoc-gen-php-grpc';

        if (!\file_exists($binaryPath)) {
            $output->writeln('protoc-gen-php-grpc binary not found."');

            return self::FAILURE;
        }

        $namespace = 'Internal\Shared\gRPC';
        $files = new Files();

        $compiler = new ProtoCompiler(
            $this->rootDir . '/generated',
            $namespace,
            $this->files,
            new ProtocCommandBuilder($this->files, $this->rootDir . '/../../proto', $binaryPath),
            new CommandExecutor(),
        );

        $compiled = [];
        foreach ($this->protoFileDirs as $dir) {
            if (!\is_dir($dir)) {
                $output->writeln("<error>Proto files dir `$dir` not found.</error>");
                continue;
            }

            if ($output->isVerbose()) {
                $output->writeln(sprintf("\n<info>Compiling <fg=cyan>`%s`</fg=cyan>:</info>", \basename($dir)));
            }

            try {
                $result = $compiler->compile($dir);
            } catch (CompileException $e) {
                throw $e;
            } catch (\Throwable $e) {
                $output->writeln("<error>Error:</error> <fg=red>{$e->getMessage()}</fg=red>");
                continue;
            }

            if ($result === []) {
                $output->writeln("<error>No files were generated for `$dir`.</error>");
                continue;
            }

            foreach ($result as $file) {
                if ($output->isVerbose()) {
                    $output->writeln(
                        \sprintf(
                            "<fg=green>•</fg=green> %s%s%s",
                            "\033[1;38m",
                            $files->relativePath($file, $this->rootDir),
                            "\e[0m",
                        ),
                    );
                }

                $compiled[] = $file;
            }
        }

        /** @var GeneratorInterface[] $generators */
        $generators = [
            new ConfigGenerator($this->files, $output),
            new ServiceClientGenerator($this->files, $output),
            new BootloaderGenerator($this->files, $output),
            new GeneratedMessagesFixer($this->files, $output),
            new ServiceInterfaceAttributesGenerator($this->files),
        ];

        foreach ($generators as $generator) {
            $output->writeln(sprintf("<info>Running <fg=cyan>`%s`</fg=cyan>:</info>", $generator::class));
            $generator->run(
                $compiled,
                $this->rootDir . '/src',
                $namespace,
            );
        }

        $output->writeln("<info>Done!</info>");

        return Command::SUCCESS;
    }
}
