<?php

namespace Uello\Tenant\Laravel\Database;

use Uello\Tenant\Helpers\TenantHelper;

class ConnectionHelper
{
    static function getConnections()
    {
        $tenants = TenantHelper::getActiveTenants();

        $connections = [];
        foreach ($tenants as $tenant) {
            $tennantUpper = strtoupper($tenant);
            $connections[$tenant] = [
                'driver' => env("DB_TENANT_{$tennantUpper}_CONNECTION"),
                'database' => database_path(env("DB_TENANT_{$tennantUpper}_PATH")),
                'prefix' => '',
                'foreign_key_constraints' => env('DB_FOREIGN_KEYS', true),
            ];
        }

        return $connections;
    }
}
