<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRecurringChargesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql')->create('recurring_charges', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('id_client')->unsigned();
            $table->foreign('id_client')
                ->references('id')->on('clients')
                ->onDelete('cascade');
            $table->string('description', 200);
            $table->tinyInteger('isPerMonth');
            $table->dateTime('date')->nullable();
            $table->integer('cost_unit')->unsigned();
            $table->integer('quantity')->unsigned();
            $table->integer('cost_total')->unsigned();
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
        Schema::dropIfExists('recurring_charges');
    }
}
