<?php

namespace Uello\Tenant\Laravel\Config;

use Uello\Tenant\Helpers\TenantHelper;

class TenantConfigHelper
{

    static function getGlobalConfigs()
    {

        $keys = array_keys($_ENV);
        $prefix = "TENANT_";
        $suffix = "_GLOBAL";

        $globalKeys = \array_filter(
            $keys,
            fn($key) => \str_starts_with($key, $prefix) && \str_ends_with($key, $suffix)
        );

        return \array_map(
            fn($key) => \substr($key, \strlen($prefix), \strlen($key) - \strlen($suffix) - \strlen($prefix)),
            $globalKeys
        );

    }

    public static function getEnv(string $key, string $tenant, $default = null)
    {
        $globalEnvKey = "TENANT_" . $key . "_GLOBAL";
        $tenantEnvKey = "TENANT_" . $key . "_" . strtoupper($tenant);

        return $_ENV[$tenantEnvKey] ?? $_ENV[$globalEnvKey] ?? $default;
    }

    public static function generate()
    {
        $tenants = TenantHelper::getTenantsFromEnv();
        $config = [];
        $globalKeys = self::getGlobalConfigs();

        foreach ($tenants as $tenant) {
            $config[$tenant] = [
                'active' => TenantHelper::isActiveFromEnv($tenant),
            ];

            foreach ($globalKeys as $key) {
                if (\str_starts_with($key, "DB_")) {
                    continue;
                }

                $config[$tenant][\strtolower($key)] = TenantConfigHelper::getEnv($key, $tenant);
            }
        }

        return $config;
    }
}
