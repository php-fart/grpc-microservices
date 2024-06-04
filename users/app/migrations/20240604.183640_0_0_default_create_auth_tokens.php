<?php

declare(strict_types=1);

namespace Migration;

use Cycle\Migrations\Migration;

class OrmDefaultA8825b20e4799f230ee219284be9b46e extends Migration
{
    protected const DATABASE = 'default';

    public function up(): void
    {
        $this->table('auth_tokens')
        ->addColumn('id', 'string', ['nullable' => false, 'defaultValue' => null, 'size' => 64])
        ->addColumn('hashed_value', 'string', ['nullable' => false, 'defaultValue' => null, 'size' => 128])
        ->addColumn('created_at', 'datetime', ['nullable' => false, 'defaultValue' => null, 'withTimezone' => false])
        ->addColumn('expires_at', 'datetime', ['nullable' => true, 'defaultValue' => null, 'withTimezone' => false])
        ->addColumn('payload', 'binary', ['nullable' => false, 'defaultValue' => null])
        ->setPrimaryKeys(['id'])
        ->create();
    }

    public function down(): void
    {
        $this->table('auth_tokens')->drop();
    }
}
