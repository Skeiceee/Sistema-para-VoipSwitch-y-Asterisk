<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProcessedCallsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql')->create('processed_calls', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->dateTime('date');
            $table->bigInteger('processed');
            $table->bigInteger('id_interconnection')->unsigned()->nullable();
            $table->foreign('id_interconnection')
                ->references('id')->on('interconnections')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('processed_calls');
    }
}
