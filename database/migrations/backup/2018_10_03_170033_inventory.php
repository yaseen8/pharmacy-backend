<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Inventory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventory', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('barcode')->nullable();
            $table->enum('type', ['medicine', 'general','cosmetics','beverage']);
            $table->integer('fk_supplier_id')->unsigned();
            $table->foreign('fk_supplier_id')->references('id')->on('supplier');
            $table->integer('fk_company_id')->unsigned();
            $table->foreign('fk_company_id')->references('id')->on('company');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('inventory');
    }
}
