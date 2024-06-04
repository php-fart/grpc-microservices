<?php

declare(strict_types=1);

namespace Generator;

use Generator\Exception\CompileException;

final class CommandExecutor
{
    public function execute(string $command): string
    {
        \exec(
            $command,
            $output,
            $exitCode,
        );

        if ($exitCode !== 0) {
            throw new CompileException(\implode("\n", $output), $exitCode);
        }

        return \trim(\implode("\n", $output), "\n ,");
    }
}
