<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAverageCallsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql')->create('average_calls', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->dateTime('date');
            $table->bigInteger('avg');
            $table->bigInteger('max');
            $table->bigInteger('min');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('mysql')->dropIfExists('average_calls');
    }
}
