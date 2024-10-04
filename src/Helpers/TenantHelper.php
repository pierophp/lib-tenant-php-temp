<?php

namespace Uello\Tenant\Helpers;

/**
 * On Laravel the env just works during the config:cache command
 */
class TenantHelper
{
    static $tenantsCache;

    static $tenantsActiveCache;

    static $prefix = 'TENANT_ACTIVE_';

    static function getTenantsFromEnv()
    {
        if (self::$tenantsCache) {
            return self::$tenantsCache;
        }

        $keys = array_keys($_ENV);

        $tenantKeys = \array_filter($keys, fn($key) => str_starts_with($key, TenantHelper::$prefix));

        self::$tenantsCache = \array_map(fn($key) => \strtolower(\substr($key, \strlen(TenantHelper::$prefix))), $tenantKeys);

        return self::$tenantsCache;
    }

    static function isActiveFromEnv($tenant): bool
    {
        return $_ENV[TenantHelper::$prefix . strtoupper($tenant)] === 'true';
    }

    static function getActiveTenantsFromEnv()
    {
        if (self::$tenantsActiveCache) {
            return self::$tenantsActiveCache;
        }

        $tenants = TenantHelper::getTenantsFromEnv();

        self::$tenantsActiveCache = \array_filter(
            $tenants,
            fn($key) => TenantHelper::isActiveFromEnv($key)
        );

        return self::$tenantsActiveCache;
    }

    static function getActiveTenantsFromConfig()
    {

        $tenants = \array_filter(
            config('tenant') ?? [],
            fn($tenant) => $tenant['active']
        );

        return \array_keys($tenants);
    }
}
