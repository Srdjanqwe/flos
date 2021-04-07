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
        if($this->command->confirm('Do you want to refresh database?')) {
            $this->command->call('migrate:fresh');
            $this->command->info('Data base was refreshed');
        }

        $this->call([
            UsersTableSeeder::class,
            BlogPostTableSeeder::class,
        ]);
    }
}
