<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ReturnSaleItems extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('returned_items', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamp('timestamp');
            $table->integer('fk_inventory_id')->unsigned();
            $table->foreign('fk_inventory_id')->references('id')->on('inventory');
            $table->integer('fk_user_id')->unsigned();
            $table->foreign('fk_user_id')->references('id')->on('users');
            $table->integer('fk_sale_id')->unsigned();
            $table->foreign('fk_sale_id')->references('id')->on('sale');
        });

        Schema::create('returned_qty', function (Blueprint $table) {
            $table->increments('id');
            $table->decimal('qty', 10,2);
            $table->integer('fk_returned_item_id')->unsigned();
            $table->foreign('fk_returned_item_id')->references('id')->on('returned_items');
            $table->integer('fk_purchase_price_id')->unsigned();
            $table->foreign('fk_purchase_price_id')->references('id')->on('purchase_price_history');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('returned_items');
        Schema::drop('returned_qty');
    }
}
