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

    public static function generate()
    {
        $tenants = TenantHelper::getTenants();
        $config = [];
        $globalKeys = self::getGlobalConfigs();

        foreach ($tenants as $tenant) {
            $config[$tenant] = [
                'active' => TenantHelper::isActive($tenant),
            ];

            foreach ($globalKeys as $key) {
                $globalEnvKey = "TENANT_" . $key . "_GLOBAL";
                $tenantEnvKey = "TENANT_" . $key . "_" . strtoupper($tenant);

                $config[$tenant][\strtolower($key)] = $_ENV[$tenantEnvKey] ?? $_ENV[$globalEnvKey];
            }
        }

        return $config;
    }
}
