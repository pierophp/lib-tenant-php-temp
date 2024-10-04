<?php

if (!function_exists('tenantConfig')) {
    /**
     * Get or set a configuration value for a specific tenant.
     *
     * @param string|null $key
     * @param string|null $tenant
     * @param mixed $default
     * @return mixed
     */
    function tenantConfig($key = null, $tenant = null, $default = null, )
    {
        if (!$key) {
            return throw new \Exception('Key is required');
        }

        if (!$tenant) {
            return throw new \Exception('Tenant is required');
        }

        $envKey = \strtolower($key);

        $configKey = "tenant.{$tenant}.{$envKey}";

        return config($configKey, $default);
    }
}
