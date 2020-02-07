<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDailyRevenuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('daily_revenues', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->dateTime('date');
            $table->integer('id_client');
            $table->string('login', 100);
            $table->bigInteger('minutes_real');
            $table->bigInteger('seconds_real_total');
            $table->bigInteger('minutes_effective');
            $table->bigInteger('seconds_effective_total');
            $table->decimal('sale', 12, 4);
            $table->decimal('cost', 12, 4);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('daily_revenues');
    }
}
