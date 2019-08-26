<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRevenuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql')->create('revenues', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->dateTime('date');
            $table->string('ip_voipswitch', 15);
            $table->string('type_customer', 15);
            $table->string('id_customer', 50);
            $table->string('customer', 50);
            $table->decimal('duration', 12, 4);
            $table->decimal('revenue', 12, 4);
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
        Schema::connection('mysql')->dropIfExists('revenues');
    }
}
