<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Product extends Migration
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
            $table->enum('type', ['tablet','capsule','syrup','surgical', 'general','cosmetics','beverage']);
            $table->integer('fk_supplier_id')->unsigned();
            $table->foreign('fk_supplier_id')->references('id')->on('supplier');
            $table->integer('fk_company_id')->unsigned();
            $table->foreign('fk_company_id')->references('id')->on('compnay');
        });

        Schema::create('stock_history', function (Blueprint $table) {
            $table->increments('id');
            $table->date('date');
            $table->integer('fk_user_id')->unsigned();
            $table->foreign('fk_user_id')->references('id')->on('users');
        });

        Schema::create('stock_item_qty_history', function (Blueprint $table) {
            $table->increments('id');
            $table->decimal('qty', 10,2);
            $table->integer('fk_stock_history_id')->unsigned();
            $table->foreign('fk_stock_history_id')->references('id')->on('stock_history');
            $table->integer('fk_inventory_id')->unsigned();
            $table->foreign('fk_inventory_id')->references('id')->on('inventory');
        });

        Schema::create('purchase_price_history', function (Blueprint $table) {
            $table->increments('id');
            $table->decimal('price', 10,2);
            $table->date('date');
            $table->integer('fk_inventory_id')->unsigned();
            $table->foreign('fk_inventory_id')->references('id')->on('inventory');
        });

        Schema::create('quantity_history', function (Blueprint $table) {
            $table->increments('id');
            $table->decimal('qty', 10,2);
            $table->date('expiry');
            $table->integer('fk_purchase_price_id')->unsigned();
            $table->foreign('fk_purchase_price_id')->references('id')->on('purchase_price_history');
            $table->integer('fk_inventory_id')->unsigned();
            $table->foreign('fk_inventory_id')->references('id')->on('inventory');
        });

        Schema::create('sale_price_history', function (Blueprint $table) {
            $table->increments('id');
            $table->decimal('price', 10,2);
            $table->timestamp('start_timestamp');
            $table->dateTime('end_timestamp')->nullable();
            $table->integer('fk_inventory_id')->unsigned();
            $table->foreign('fk_inventory_id')->references('id')->on('inventory');
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
