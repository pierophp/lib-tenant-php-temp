<?php

namespace Uello\Tenant\Helpers;

class TenantHelper
{
    static function getActiveTenants()
    {
        $prefix = 'TENANT_ACTIVE_';
        $keys = array_keys($_ENV);
        $tenantKeys = \array_filter($keys, fn($key) => str_starts_with($key, $prefix));
        $activeTenants = \array_filter($tenantKeys, fn($key) => $_ENV[$key] === 'true');

        return \array_map(fn($key) => \substr($key, \strlen($prefix)), $activeTenants);
    }
}
