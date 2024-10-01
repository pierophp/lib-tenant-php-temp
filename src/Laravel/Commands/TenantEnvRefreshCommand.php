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
    protected $description = 'Command description';

    protected $envFiles = ["/env/.env.tenant"];

    protected $interval = 10;

    protected $command = "php artisan config:cache";

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Command to execute when any file changes


        // Polling interval (in seconds)

        // Initialize an associative array to store the last MD5 checksums
        $lastMd5 = [];

        // Populate the $lastMd5 array with the initial checksums
        foreach ($this->envFiles as $file) {
            if (file_exists($file)) {
                $lastMd5[$file] = md5_file($file);
            } else {
                echo "Warning: $file does not exist.\n";
            }
        }

        // Infinite loop to poll files
        while (true) {
            foreach ($this->envFiles as $file) {
                if (file_exists($file)) {
                    // Get the current MD5 checksum
                    $currentMd5 = md5_file($file);

                    echo "Current md5: $currentMd5\n";
                    echo "Last md5: " . $lastMd5[$file] . "\n";

                    // Check if the MD5 has changed
                    if ($lastMd5[$file] !== $currentMd5) {
                        echo "Change detected in $file. Running $this->command.\n";

                        // Run the command
                        exec($this->command);

                        // Update the last known MD5 checksum
                        $lastMd5[$file] = $currentMd5;
                    }
                } else {
                    echo "Warning: $file does not exist.\n";
                }
            }

            // Sleep for the defined interval before checking again
            sleep($this->interval);
        }
    }
}
