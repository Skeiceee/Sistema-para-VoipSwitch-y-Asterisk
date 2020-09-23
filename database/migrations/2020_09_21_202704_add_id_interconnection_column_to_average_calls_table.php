<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIdInterconnectionColumnToAverageCallsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('average_calls', function (Blueprint $table) {
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
        Schema::table('average_calls', function (Blueprint $table) {
            //
        });
    }
}
