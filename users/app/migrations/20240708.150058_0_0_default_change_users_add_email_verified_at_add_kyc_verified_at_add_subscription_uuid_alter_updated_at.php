<?php

declare(strict_types=1);

namespace Migration;

use Cycle\Migrations\Migration;

class OrmDefault25c3b3013ad47ae9def17fe55a1b8396 extends Migration
{
    protected const DATABASE = 'default';

    public function up(): void
    {
        $this->table('users')
            ->addColumn(
                'email_verified_at',
                'datetime',
                ['nullable' => true, 'defaultValue' => null, 'withTimezone' => false],
            )
            ->addColumn(
                'kyc_verified_at',
                'datetime',
                ['nullable' => true, 'defaultValue' => null, 'withTimezone' => false],
            )
            ->addColumn('subscription_uuid', 'uuid', ['nullable' => true, 'defaultValue' => null])
            ->alterColumn(
                'updated_at',
                'datetime',
                ['nullable' => false, 'defaultValue' => null, 'withTimezone' => false],
            )
            ->update();
    }

    public function down(): void
    {
        $this->table('users')
            ->alterColumn(
                'updated_at',
                'timestamp',
                ['nullable' => true, 'defaultValue' => null, 'withTimezone' => false],
            )
            ->dropColumn('email_verified_at')
            ->dropColumn('kyc_verified_at')
            ->dropColumn('subscription_uuid')
            ->update();
    }
}
