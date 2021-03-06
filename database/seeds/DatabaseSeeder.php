<?php

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
        $this->call(TeamsTableSeeder::class);
        $this->call(DivisionsTableSeeder::class);
        $this->call(OddsTableSeeder::class);
        $this->call(StarsTableSeeder::class);
    }
}
