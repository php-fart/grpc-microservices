<?php

declare(strict_types=1);

namespace Migration;

use Cycle\Migrations\Migration;

class OrmDefaultDc8ce5600f0f564bb96ac4bffb58d058 extends Migration
{
    protected const DATABASE = 'default';

    public function up(): void
    {
        $this->table('users')
        ->addColumn('fist_name', 'string', ['nullable' => true, 'defaultValue' => null, 'size' => 255])
        ->addColumn('last_name', 'string', ['nullable' => true, 'defaultValue' => null, 'size' => 255])
        ->addColumn('language', 'string', ['nullable' => false, 'defaultValue' => 'ru', 'size' => 255])
        ->update();
    }

    public function down(): void
    {
        $this->table('users')
        ->dropColumn('fist_name')
        ->dropColumn('last_name')
        ->dropColumn('language')
        ->update();
    }
}
