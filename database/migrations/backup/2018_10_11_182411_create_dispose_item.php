<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDisposeItem extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dispose_items', function (Blueprint $table) {
            $table->increments('id');
            $table->decimal('qty', 10,2);
            $table->integer('fk_quantity_history_id')->unsigned();
            $table->foreign('fk_quantity_history_id')->references('id')->on('quantity_history');
            $table->integer('fk_invetory_id')->unsigned();
            $table->foreign('fk_invetory_id')->references('id')->on('inventory');
            $table->integer('fk_user_id')->unsigned();
            $table->foreign('fk_user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        
    }
}
