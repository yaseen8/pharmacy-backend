<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Sale extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sale', function (Blueprint $table) {
            $table->increments('id');
            $table->string('receipt_code', 40);
            $table->decimal('total', 10,2);
            $table->decimal('discount', 10,2);
            $table->decimal('grand_total', 10,2);
            $table->enum('payment', ['cash', 'credit']);
            $table->timestamp('timestamp');
            $table->integer('fk_user_id')->unsigned();
            $table->foreign('fk_user_id')->references('id')->on('users');
        });

        Schema::create('sale_items', function (Blueprint $table) {
            $table->increments('id');
            $table->decimal('total', 10,2);
            $table->integer('fk_inventory_id')->unsigned();
            $table->foreign('fk_inventory_id')->references('id')->on('inventory');
            $table->integer('fk_sale_price_id')->unsigned();
            $table->foreign('fk_sale_price_id')->references('id')->on('sale_price_history');
            $table->integer('fk_sale_id')->unsigned();
            $table->foreign('fk_sale_id')->references('id')->on('sale');
        });

        Schema::create('purchased_qty', function (Blueprint $table) {
            $table->increments('id');
            $table->decimal('qty', 10,2);
            $table->integer('fk_purchase_price_id')->unsigned();
            $table->foreign('fk_purchase_price_id')->references('id')->on('purchase_price_history');
            $table->integer('fk_sale_item_id')->unsigned();
            $table->foreign('fk_sale_item_id')->references('id')->on('sale_items');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('sale');
        Schema::drop('sale_items');
        Schema::drop('purchased_qty');
    }
}
