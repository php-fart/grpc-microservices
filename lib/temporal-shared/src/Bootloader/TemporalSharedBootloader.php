<?php

declare(strict_types=1);

namespace Internal\Shared\Temporal\Bootloader;

use Spiral\Boot\Bootloader\Bootloader;
use Spiral\Boot\DirectoriesInterface;
use Spiral\Tokenizer\Bootloader\TokenizerBootloader;

final class TemporalSharedBootloader extends Bootloader
{
    public function init(
        TokenizerBootloader $tokenizer,
        DirectoriesInterface $dirs,
    ): void {
        $tokenizer->addDirectory($dirs->get('root') . 'vendor/ms/temporal-shared/src');
    }
}