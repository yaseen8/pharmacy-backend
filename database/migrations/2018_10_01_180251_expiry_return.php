<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ExpiryReturn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('expiry_return', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamp('timestamp');
            $table->integer('fk_supplier_id')->unsigned();
            $table->foreign('fk_supplier_id')->references('id')->on('supplier');
            $table->integer('fk_user_id')->unsigned();
            $table->foreign('fk_user_id')->references('id')->on('users');
        });

        Schema::create('expiry_items', function (Blueprint $table) {
            $table->increments('id');
            $table->decimal('qty', 10,2);
            $table->integer('fk_expiry_return_id')->unsigned();
            $table->foreign('fk_expiry_return_id')->references('id')->on('expiry_return');
            $table->integer('fk_invetory_id')->unsigned();
            $table->foreign('fk_invetory_id')->references('id')->on('inventory');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('expiry_return');
        Schema::drop('expiry_items');
    }
}
