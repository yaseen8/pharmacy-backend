<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePatientChargesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('patient_charges', function (Blueprint $table) {
            $table->increments('id');
            $table->decimal('payment');
            $table->timestamp('timestamp');
            $table->integer('sale_id')->unsigned();
            $table->foreign('sale_id')->references('id')->on('sale');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('patient_charges');
    }
}
