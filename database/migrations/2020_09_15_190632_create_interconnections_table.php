<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInterconnectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('interconnections', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 150);
            $table->string('connection_name', 150);
            $table->string('connection_no_strict_name', 150);
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
        Schema::dropIfExists('interconnections');
    }
}
