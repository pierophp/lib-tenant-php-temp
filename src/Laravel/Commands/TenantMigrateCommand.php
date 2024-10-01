<?php

namespace Uello\Tenant\Laravel\Commands;

use Illuminate\Console\Command;
use Uello\Tenant\Laravel\Database\ConnectionHelper;

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
        $connections = \array_keys(ConnectionHelper::getConnections());
        foreach ($connections as $connection) {
            $this->call('migrate', [
                '--database' => $connection,
                '--no-interaction' => true,
            ]);
        }
    }
}
