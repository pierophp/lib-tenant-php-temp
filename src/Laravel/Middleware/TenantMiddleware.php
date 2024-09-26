<?php

namespace Uello\Tenant\Laravel\Middleware;

use Closure;
use Illuminate\Support\Facades\DB;

class TenantMiddleware
{
    public function handle($request, Closure $next)
    {
        // Assuming you have some way to identify the tenant, e.g., subdomain, request parameter, etc.
        $tenant = $this->getTenantFromRequest($request);

        if ($tenant) {
            DB::setDefaultConnection($tenant);
        }

        return $next($request);
    }

    private function getTenantFromRequest($request)
    {
        return $request->header('X-Tenant');
    }
}
