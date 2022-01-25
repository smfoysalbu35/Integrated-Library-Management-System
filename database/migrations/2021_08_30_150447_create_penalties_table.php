<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePenaltiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('penalties', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('return_book_id')->unsigned();
            $table->foreign('return_book_id')
                ->references('id')->on('return_books')
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

            $table->date('penalty_due_date');
            $table->decimal('amount', 10, 2);
            $table->integer('overdue');
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
        Schema::dropIfExists('penalties');
    }
}
