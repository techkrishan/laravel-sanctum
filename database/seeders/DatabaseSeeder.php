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
        $this->call([
            LookupSeeder::class,
        ]);
        // \App\Models\User::factory(1000)->create();
        // \App\Models\Question::factory(10000)->create();
    }
}
