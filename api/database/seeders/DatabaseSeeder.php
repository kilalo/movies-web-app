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
        $path = storage_path('dump.sql');
        \DB::unprepared(file_get_contents($path));
        $this->command->info('Database dump imported successfully !');
    }
}   
