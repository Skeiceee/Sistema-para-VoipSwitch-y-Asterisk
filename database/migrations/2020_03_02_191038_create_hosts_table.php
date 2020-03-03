<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql')->create('hosts', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_sub')->unsigned();
            $table->foreign('id_sub')->references('id')->on('subredes')->change();
            $table->string('server', 50)->nullable();
            $table->string('hostname', 100)->nullable();
            $table->string('ipvmware', 15)->nullable();
            $table->string('ip', 15);
            $table->string('username', 50)->nullable();
            $table->string('password', 500)->nullable();
            $table->string('port', 5)->nullable();
            $table->string('obs', 500)->nullable();
            $table->char('estado', 1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hosts');
    }
}
