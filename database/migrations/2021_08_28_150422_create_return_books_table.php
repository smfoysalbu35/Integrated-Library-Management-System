<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReturnBooksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('return_books', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('borrow_id')->unsigned();
            $table->foreign('borrow_id')
                ->references('id')->on('borrows')
                ->onUpdate('cascade')
                ->onDelete('cascade');

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

            $table->date('return_date');
            $table->time('return_time');
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
        Schema::dropIfExists('return_books');
    }
}
