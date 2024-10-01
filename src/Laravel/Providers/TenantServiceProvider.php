<?php

namespace Uello\Tenant\Laravel\Providers;

use Illuminate\Support\ServiceProvider;
use Uello\Tenant\Laravel\Commands\TenantEnvGenerateCommand;
use Uello\Tenant\Laravel\Commands\TenantEnvRefreshCommand;
use Uello\Tenant\Laravel\Commands\TenantMigrateCommand;

class TenantServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->commands('tenant.migrate');
        $this->commands('tenant.env-generate');
        $this->commands('tenant.env-refresh');
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
        $this->app->singleton('tenant.migrate', function () {
            return new TenantMigrateCommand();
        });

        $this->app->singleton('tenant.env-generate', function () {
            return new TenantEnvGenerateCommand();
        });

        $this->app->singleton('tenant.env-refresh', function () {
            return new TenantEnvRefreshCommand();
        });
    }
}