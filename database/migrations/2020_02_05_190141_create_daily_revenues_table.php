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
            $table->integer('id_client');
            $table->integer('login');
            $table->bigInteger('minutes_real');
            $table->bigInteger('seconds_real_total');
            $table->bigInteger('minutes_affective');
            $table->bigInteger('seconds_effective_total');
            $table->decimal('sale', 12, 4);
            $table->decimal('cost', 12, 4);
            $table->timestamps();
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
