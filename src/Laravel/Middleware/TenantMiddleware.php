<?php

namespace Uello\Tenant\Laravel\Middleware;

use Closure;
use Illuminate\Support\Facades\DB;

class TenantMiddleware
{
    public function handle($request, Closure $next)
    {
        $tenant = $this->getTenantFromRequest($request);

        $isValid = $this->validateTenant($tenant);

        $request->route()->setParameter('tenant', $tenant);

        if (!$isValid) {
            return $next($request);
        }

        if ($tenant) {
            DB::setDefaultConnection($tenant);
        }

        return $next($request);
    }

    private function getTenantFromRequest($request)
    {
        if ($request->hasHeader('X-Tenant')) {
            return $request->header('X-Tenant');
        }

        // PubSub Message
        $message = $request->has('message') ? $request->input('message') : null;
        if (!empty($message['attributes']['tenant'])) {
            return $message['attributes']['tenant'];
        }

        abort(403, 'Tenant is Required');
    }

    private function validateTenant($tenant): bool
    {
        $tenantConfig = config('tenant.' . $tenant);
        if ($tenantConfig === null) {
            abort(403, 'Tenant doesn\'t exist');
            return false;
        }

        if ($tenantConfig['active'] === false) {
            abort(403, 'Tenant is inactive');
            return false;
        }

        return true;
    }
}
