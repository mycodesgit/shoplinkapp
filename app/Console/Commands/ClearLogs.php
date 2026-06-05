<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use File;

class ClearLogs extends Command
{
    protected $signature = 'logs:clear';
    protected $description = 'Clear all log files in storage/logs';

    public function handle()
    {
        $logPath = storage_path('logs');

        $files = File::files($logPath);

        foreach ($files as $file) {
            File::put($file->getPathname(), ''); // Truncate each log file
        }

        $this->info('Logs have been cleared!');
    }
}
