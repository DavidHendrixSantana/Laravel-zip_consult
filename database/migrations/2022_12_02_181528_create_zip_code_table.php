<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateZipCodeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('zip_code', function (Blueprint $table) {
            $table->string('zip_code');
            $table->string('locality');
            $table->primary('zip_code');
            $table->integer('federal_entity');
            $table->foreign('federal_entity')->references('key')->on('federal_entity');




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
        Schema::dropIfExists('zip_code');
    }
}
