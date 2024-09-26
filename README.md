# Lib Tenant PHP

## Laravel

### app/Http/Kernel.php 
Add \Uello\Tenant\Laravel\Middleware\TenantMiddleware::class to both "web" and "api" middleware groups.

```php
class Kernel extends HttpKernel
{
    protected $middlewareGroups = [
        'web' => [
            // ...
            \Uello\Tenant\Laravel\Middleware\TenantMiddleware::class,
        ],

        'api' => [
            // ...
            \Uello\Tenant\Laravel\Middleware\TenantMiddleware::class,
        ],
    ];
}
```

### config/database.php

Add ConnectionHelper::generateConnections() to your database file.

```php
use Uello\Tenant\Laravel\Database\ConnectionHelper;

return [
    'default' => env('DB_CONNECTION', 'mysql'),
    'connections' => ConnectionHelper::generateConnections(),
    // ...
]
```
