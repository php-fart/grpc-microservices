<?php

declare(strict_types=1);

use Cycle\Schema\Generator\Migrations\Strategy\MultipleFilesStrategy;

return [

    'directory' => directory('app') . 'migrations/',

    'table' => 'migrations',

    'strategy' => MultipleFilesStrategy::class,

    'safe' => env('APP_ENV') === 'local',
];
