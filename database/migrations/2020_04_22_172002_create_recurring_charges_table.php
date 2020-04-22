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
        Schema::create('recurring_charges', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('description', 200);
            $table->integer('cost_unit')->unsigned();
            $table->integer('quantity')->unsigned();
            $table->integer('cost_total')->unsigned();
            $table->tinyInteger('isPerMonth');
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
