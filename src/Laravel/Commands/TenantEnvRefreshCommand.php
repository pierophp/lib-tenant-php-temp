<?php

namespace Uello\Tenant\Laravel\Commands;

use Illuminate\Console\Command;

class TenantEnvRefreshCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tenant:env-refresh';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Keep listening the .env files and refresh the configuration cache when it changes';

    protected $envFiles = [
        // "/env/.env.tenant", 
        "/home/piero/uello/pocs/tenant-php-poc/.env.base",
        "/home/piero/uello/pocs/env/.env"
    ];

    protected $interval = 10;
    public function handle()
    {
        $lastMd5 = [];

        foreach ($this->envFiles as $file) {
            if (file_exists($file)) {
                echo "Listening to changes on: $file\n";
                $lastMd5[$file] = md5_file($file);
            } else {
                echo "Warning: $file does not exist.\n";
            }
        }

        while (true) {
            foreach ($this->envFiles as $file) {
                if (file_exists($file)) {
                    $currentMd5 = md5_file($file);

                    if ($lastMd5[$file] !== $currentMd5) {
                        echo "Change detected in $file. Running php artisan config:cache.\n";

                        $this->call('tenant:env-generate');

                        $lastMd5[$file] = $currentMd5;
                    }
                } else {
                    echo "Warning: $file does not exist.\n";
                }
            }

            sleep($this->interval);
        }
    }
}
