<?php

namespace Uello\Tenant\Laravel\Database;

class ConnectionHelper
{
    static function getConnections()
    {
        $keys = array_keys($_ENV);
        $dbTenantKeys = \array_filter($keys, fn($key) => str_starts_with($key, 'DB_TENANT_'));
        $dbTenantConnectionKeys = \array_filter($dbTenantKeys, fn($key) => str_ends_with($key, '_CONNECTION'));

        $connections = [];
        foreach ($dbTenantConnectionKeys as $key) {
            $tenant = str_replace(['DB_TENANT_', '_CONNECTION'], '', $key);

            $connections[strtolower($tenant)] = [
                'driver' => env("DB_TENANT_{$tenant}_CONNECTION"),
                'database' => database_path(env("DB_TENANT_{$tenant}_PATH")),
                'prefix' => '',
                'foreign_key_constraints' => env('DB_FOREIGN_KEYS', true),
            ];
        }

        return $connections;
    }
}
