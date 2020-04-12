<?php

use Illuminate\Database\Seeder;

class OddsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::unprepared(file_get_contents('_sql/odds.sql'));
    }
}
