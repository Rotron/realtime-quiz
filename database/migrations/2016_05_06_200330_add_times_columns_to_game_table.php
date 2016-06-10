<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTimesColumnsToGameTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('games', function (Blueprint $table) {
            $table->integer('intro_time')->default(env('TIME_INTRO', 60));
            $table->integer('quiz_time')->default(env('TIME_QUIZ', 60));
            $table->integer('discuss_time')->default(env('TIME_DISCUSS', 60));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('games', function (Blueprint $table) {
            //
        });
    }
}
