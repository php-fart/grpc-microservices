<?php

declare(strict_types=1);

namespace Migration;

use Cycle\Migrations\Migration;

class OrmDefaultE1267a582ec102c35ff8e1dc2a945344 extends Migration
{
    protected const DATABASE = 'default';

    public function up(): void
    {
        $this->table('users')
        ->addColumn('uuid', 'uuid', ['nullable' => false, 'defaultValue' => null])
        ->addColumn('email', 'string', ['nullable' => false, 'defaultValue' => null, 'unique' => true, 'size' => 255])
        ->addColumn('password', 'string', ['nullable' => false, 'defaultValue' => null, 'size' => 64])
        ->setPrimaryKeys(['uuid'])
        ->create();
    }

    public function down(): void
    {
        $this->table('users')->drop();
    }
}
