<?php

namespace App\Console\Commands;

use App\Exports\ScheduleExport;
use App\Models\Schedule;
use Illuminate\Console\Command;
use Maatwebsite\Excel\Facades\Excel;

class ExportRoutes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'vaos:export-routes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    private $excel;
    public function handle()
    {
        Excel::store(new ScheduleExport(), 'schedules.xlsx');
    }
}
