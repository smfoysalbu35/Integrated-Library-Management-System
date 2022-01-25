<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePatronAccountLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('patron_account_logs', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('patron_account_id')->unsigned();
            $table->foreign('patron_account_id')
                ->references('id')->on('patron_accounts')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->date('date_in');
            $table->time('time_in');
            $table->date('date_out')->nullable();
            $table->time('time_out')->nullable();
            $table->tinyInteger('status')->default(1);
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
        Schema::dropIfExists('patron_account_logs');
    }
}
