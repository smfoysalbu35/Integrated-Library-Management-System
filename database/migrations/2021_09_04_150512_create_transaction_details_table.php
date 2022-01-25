<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaction_details', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('transaction_id')->unsigned();
            $table->foreign('transaction_id')
                ->references('id')->on('transactions')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->integer('accession_id')->unsigned();
            $table->foreign('accession_id')
                ->references('id')->on('accessions')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->integer('penalty_id')->unsigned();
            $table->foreign('penalty_id')
                ->references('id')->on('penalties')
                ->onUpdate('cascade')
                ->onDelete('cascade');
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
        Schema::dropIfExists('transaction_details');
    }
}
