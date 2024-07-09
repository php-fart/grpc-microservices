<?php

declare(strict_types=1);

namespace Migration;

use Cycle\Migrations\Migration;

class OrmDefaultEc8e6c29e9c08d441d37a64422a0a80b extends Migration
{
    protected const DATABASE = 'default';

    public function up(): void
    {
        $this->table('subscriptions')
            ->addColumn('created_at', 'datetime', [
                'nullable' => false,
                'defaultValue' => 'CURRENT_TIMESTAMP',
                'withTimezone' => false,
            ])
            ->addColumn('updated_at', 'datetime', ['nullable' => false, 'defaultValue' => null, 'withTimezone' => false],
            )
            ->addColumn('uuid', 'uuid', ['nullable' => false, 'defaultValue' => null])
            ->addColumn('name', 'string', ['nullable' => false, 'defaultValue' => null, 'unique' => true, 'size' => 255],
            )
            ->addColumn('trial_days', 'integer', ['nullable' => false, 'defaultValue' => null])
            ->addColumn('price', 'double', ['nullable' => false, 'defaultValue' => null])
            ->setPrimaryKeys(['uuid'])
            ->create();
    }

    public function down(): void
    {
        $this->table('subscriptions')->drop();
    }
}
