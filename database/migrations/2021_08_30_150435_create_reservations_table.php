<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReservationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('patron_id')->unsigned();
            $table->foreign('patron_id')
                ->references('id')->on('patrons')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->integer('accession_id')->unsigned();
            $table->foreign('accession_id')
                ->references('id')->on('accessions')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->date('reservation_date');
            $table->time('reservation_time');
            $table->date('reservation_end_date');
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
        Schema::dropIfExists('reservations');
    }
}
