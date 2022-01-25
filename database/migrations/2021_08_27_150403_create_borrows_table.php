<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBorrowsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('borrows', function (Blueprint $table) {
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

            $table->date('borrow_date');
            $table->time('borrow_time');
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
        Schema::dropIfExists('borrows');
    }
}
