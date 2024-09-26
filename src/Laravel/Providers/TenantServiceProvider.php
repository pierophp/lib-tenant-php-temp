<?php

namespace Uello\Tenant\Laravel\Providers;

use Illuminate\Support\ServiceProvider;
use Uello\Tenant\Laravel\Commands\TenantMigrateCommand;

class TenantServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->commands('tenant.generate-config');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerCommand();
    }

    /**
     * Register the Artisan command.
     */
    protected function registerCommand()
    {
        $this->app->singleton('tenant.generate-config', function () {
            return new TenantMigrateCommand();
        });
    }
}