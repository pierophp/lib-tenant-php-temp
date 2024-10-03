<?php

namespace Uello\Tenant\Helpers;

class TenantHelper
{
    static $tenantsCache;

    static $tenantsActiveCache;

    static $prefix = 'TENANT_ACTIVE_';

    static function getTenants()
    {
        if (self::$tenantsCache) {
            return self::$tenantsCache;
        }

        $keys = array_keys($_ENV);

        $tenantKeys = \array_filter($keys, fn($key) => str_starts_with($key, TenantHelper::$prefix));

        self::$tenantsCache = \array_map(fn($key) => \strtolower(\substr($key, \strlen(TenantHelper::$prefix))), $tenantKeys);

        return self::$tenantsCache;
    }

    static function isActive($tenant): bool
    {
        return $_ENV[TenantHelper::$prefix . strtoupper($tenant)] === 'true';
    }

    static function getActiveTenants()
    {
        if (self::$tenantsActiveCache) {
            return self::$tenantsActiveCache;
        }

        $tenants = TenantHelper::getTenants();

        self::$tenantsActiveCache = \array_filter(
            $tenants,
            fn($key) => TenantHelper::isActive($key)
        );

        return self::$tenantsActiveCache;
    }
}
