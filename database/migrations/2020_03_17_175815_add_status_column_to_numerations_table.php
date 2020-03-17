<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusColumnToNumerationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('numerations', function (Blueprint $table) {
            $table->bigInteger('status')->unsigned()->nullable();
            $table->foreign('status')
                ->references('id')->on('status')
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
        Schema::table('numerations', function (Blueprint $table) {
            //
        });
    }
}
