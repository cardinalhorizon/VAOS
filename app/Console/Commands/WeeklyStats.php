<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class WeeklyStats extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'vaos:weekly-stats';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sends Weekly Stats to specified users.';

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
    public function handle()
    {
        //
    }
}
