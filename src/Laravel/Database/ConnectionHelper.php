<?php

namespace Uello\Tenant\Laravel\Database;

use Uello\Tenant\Helpers\TenantHelper;
use Uello\Tenant\Laravel\Config\TenantConfigHelper;

class ConnectionHelper
{
    static function getConnections()
    {
        $tenants = TenantHelper::getActiveTenantsFromEnv();

        $connections = [];
        foreach ($tenants as $tenant) {
            $connections[$tenant] = [
                'driver' => TenantConfigHelper::getEnv("DB_CONNECTION", $tenant),
                'host' => TenantConfigHelper::getEnv("DB_HOST", $tenant),
                'database' => TenantConfigHelper::getEnv("DB_DATABASE", $tenant),
                'username' => TenantConfigHelper::getEnv("DB_USERNAME", $tenant),
                'password' => TenantConfigHelper::getEnv("DB_PASSWORD", $tenant),
                'port' => TenantConfigHelper::getEnv("DB_PORT", $tenant, '3306'),
                'unix_socket' => TenantConfigHelper::getEnv("DB_SOCKET", $tenant),
                'foreign_key_constraints' => TenantConfigHelper::getEnv("DB_FOREIGN_KEYS", $tenant, true),
                'prefix' => '',
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
                'prefix_indexes' => true,
                'strict' => true,
                'engine' => null,
                'options' => extension_loaded('pdo_mysql') ? array_filter([
                    \PDO::MYSQL_ATTR_SSL_CA => TenantConfigHelper::getEnv("MYSQL_ATTR_SSL_CA", $tenant),
                ]) : [],
            ];
        }

        return $connections;
    }
}
