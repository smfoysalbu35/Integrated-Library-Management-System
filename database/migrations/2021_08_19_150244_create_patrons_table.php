<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePatronsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('patrons', function (Blueprint $table) {
            $table->increments('id');
            $table->string('patron_no', 45)->unique();
            $table->string('last_name', 45);
            $table->string('first_name', 45);
            $table->string('middle_name', 45)->nullable();

            $table->string('contact_no', 45);
            $table->string('image', 255);

            $table->string('house_no', 100);
            $table->string('street', 100);
            $table->string('barangay', 100);
            $table->string('municipality', 100);
            $table->string('province', 100);

            $table->integer('patron_type_id')->unsigned();
            $table->foreign('patron_type_id')
                ->references('id')->on('patron_types')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->integer('section_id')->unsigned();
            $table->foreign('section_id')
                ->references('id')->on('sections')
                ->onUpdate('cascade')
                ->onDelete('cascade');

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
        Schema::dropIfExists('patrons');
    }
}
