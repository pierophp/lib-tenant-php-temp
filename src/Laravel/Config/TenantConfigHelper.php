<?php

namespace Uello\Tenant\Laravel\Config;

use Uello\Tenant\Helpers\TenantHelper;

class TenantConfigHelper
{
    public static function generate()
    {
        $tenants = TenantHelper::getTenants();
        $config = [];
        foreach ($tenants as $tenant) {
            $config[$tenant] = [
                'active' => TenantHelper::isActive($tenant),
            ];
        }

        return $config;
    }
}
