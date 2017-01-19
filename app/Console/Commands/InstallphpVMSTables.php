<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class InstallphpVMSTables extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'vaos:installphpVMS';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'VAOS: Install phpVMS Tables and setup Legacy Environment';

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
        $this->info('Running phpVMS database install using settings from .env');
        DB::connection('phpVMS')->unprepared(file_get_contents('legacy/install.sql'));
    }
}
