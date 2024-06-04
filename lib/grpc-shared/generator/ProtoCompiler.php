<?php

declare(strict_types=1);

namespace Generator;

use Generator\Exception\CompileException;
use Spiral\Files\FilesInterface;

final readonly class ProtoCompiler
{
    private string $baseNamespace;

    public function __construct(
        private string $basePath,
        string $baseNamespace,
        private FilesInterface $files,
        private ProtocCommandBuilder $commandBuilder,
        private CommandExecutor $executor,
    ) {
        $this->baseNamespace = \str_replace('\\', '/', \rtrim($baseNamespace, '\\'));
    }

    /**
     * @throws CompileException
     */
    public function compile(string $dir): array
    {
        $tmpDir = $this->tmpDir();

        $command = $this->commandBuilder->build($dir, $tmpDir);
        if ($command === '') {
            return [];
        }

        $output = $this->executor->execute($command);

        if ($output !== '') {
            $this->files->deleteDirectory($tmpDir);
            throw new CompileException($output);
        }

        // copying files (using relative path and namespace)
        $result = [];
        foreach ($this->files->getFiles($tmpDir) as $file) {
            $result[] = $this->copy($tmpDir, $file);
        }

        $this->files->deleteDirectory($tmpDir);

        return $result;
    }

    private function copy(string $tmpDir, string $file): string
    {
        $source = \ltrim($this->files->relativePath($file, $tmpDir), '\\/');
        if (\str_starts_with($source, $this->baseNamespace)) {
            $source = \ltrim(\substr($source, \strlen($this->baseNamespace)), '\\/');
        }

        $target = $this->files->normalizePath($this->basePath . '/' . $source);

        $this->files->ensureDirectory(\dirname($target));
        $this->files->copy($file, $target);

        return $target;
    }

    private function tmpDir(): string
    {
        $directory = \sys_get_temp_dir() . '/' . \spl_object_hash($this);
        $this->files->ensureDirectory($directory);

        return $this->files->normalizePath($directory, true);
    }
}
