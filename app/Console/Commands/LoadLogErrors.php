<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use App\Events\LogErrorsLoaded;

class LoadLogErrors extends Command
{
    protected $signature = 'logs:load-errors';
    protected $description = 'Load error log titles and store them in the database';

    public function handle()
    {
        $logTitles = [];
        $logs = Storage::disk('logs')->files();

        foreach ($logs as $log) {
            $content = Storage::disk('logs')->get($log);
            preg_match_all('/^\[[^\]]+\]\s([^\n]+)/m', $content, $matches);
            $logTitles = array_merge($logTitles, $matches[1]);
        }

        $this->info('Log errors loaded and stored successfully.');
    }
}
