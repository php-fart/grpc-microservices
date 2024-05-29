<?php

declare(strict_types=1);

namespace Migration;

use Cycle\Migrations\Migration;

class OrmDefaultDf800ef5ab04eee8c5dac20085b1430b extends Migration
{
    protected const DATABASE = 'default';

    public function up(): void
    {
        $this->table('users')
            ->addColumn('created_at', 'datetime', [
                'nullable' => false,
                'defaultValue' => 'CURRENT_TIMESTAMP',
                'withTimezone' => false,
            ])
            ->addColumn(
                'updated_at',
                'datetime',
                ['nullable' => false, 'defaultValue' => null, 'withTimezone' => false],
            )
            ->update();
    }

    public function down(): void
    {
        $this->table('users')
            ->dropColumn('created_at')
            ->dropColumn('updated_at')
            ->update();
    }
}
