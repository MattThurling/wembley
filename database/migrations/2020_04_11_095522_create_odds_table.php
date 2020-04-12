<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOddsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('odds', function (Blueprint $table) {
            $table->id();
            $table->smallInteger('goals')->unsigned();
            $table->smallInteger('division_id')->unsigned();
            $table->smallInteger('home_odds')->unsigned();
            $table->smallInteger('away_odds')->unsigned();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('odds');
    }
}
