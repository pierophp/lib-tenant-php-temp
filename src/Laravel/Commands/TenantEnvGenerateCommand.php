<?php

namespace Uello\Tenant\Laravel\Commands;

use Illuminate\Console\Command;

class TenantEnvGenerateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tenant:env-generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Compiles all the env files into a single .env file';

    protected $envFiles = [
        // "/env/.env.tenant",
        "/home/piero/uello/pocs/tenant-php-poc/.env.base",
        "/home/piero/uello/pocs/env/.env"
    ];


    public function handle()
    {
        $outputEnvFile = base_path() . '/.env';

        $content = '';
        foreach ($this->envFiles as $file) {
            if (file_exists($file)) {
                $content .= "## $file\n";
                $content .= file_get_contents($file);
                $content .= "\n";
            } else {
                echo "Warning: $file does not exist.\n";
            }
        }

        file_put_contents($outputEnvFile, $content);

        $this->call('config:cache');
    }
}
