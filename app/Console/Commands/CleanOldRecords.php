<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CleanOldRecords extends Command
{
    protected $signature = 'records:clean';

    protected $description = 'Supprimer les erreurs datant de plus d\'un mois';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $table = 'error_logs';
        $dateColumn = 'created_at';

        $dateLimit = Carbon::now()->subMonth();

        DB::table($table)->where($dateColumn, '<', $dateLimit)->delete();

        $this->info('Les anciennes erreurs ont été supprimées');
    }
}
