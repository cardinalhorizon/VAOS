<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);

        //Only for DEV
        $this->call(LaratrustSeeder::class);

        //$this->call(InstallSeeder::class);
    }
}
