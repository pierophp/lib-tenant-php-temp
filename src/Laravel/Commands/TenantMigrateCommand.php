<?php

namespace Uello\Tenant\Laravel\Commands;

use Illuminate\Console\Command;
use Log;
use Uello\Tenant\Helpers\TenantHelper;


class TenantMigrateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tenant:migrate-install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'It executes the migration for all tenant databases';

    public function handle()
    {
        $connections = TenantHelper::getActiveTenantsFromConfig();

        foreach ($connections as $connection) {
            Log::info("Migrating database: $connection");


            $this->call('migrate', [
                '--database' => $connection,
                '--no-interaction' => true,
            ]);
        }
    }
}
