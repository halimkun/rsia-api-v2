<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        
        // Testing Log
        // \Log::info('DatabaseSeeder is called');

        // Testing Berkas Logger
        \App\Helpers\Logger\RSIALogger::berkas('TESTING . SEEDER CALLED', 'warning', ['context' => 'DatabaseSeeder is called']);
    }
}
