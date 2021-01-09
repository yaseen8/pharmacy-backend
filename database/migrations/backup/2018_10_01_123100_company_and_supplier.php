<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CompanyAndSupplier extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {


        Schema::create('supplier', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('mobile');
            $table->string('email')->nullable();
            $table->string('landline')->nullable();
        });

        Schema::create('supplier_payment', function (Blueprint $table) {
            $table->increments('id');
            $table->decimal('amount', 10,2);
            $table->timestamp('timestamp');
            $table->enum('payment_via', ['cash', 'cheque']);
            $table->integer('fk_supplier_id')->unsigned();
            $table->foreign('fk_supplier_id')->references('id')->on('supplier');
            $table->integer('fk_user_id')->unsigned();
            $table->foreign('fk_user_id')->references('id')->on('users');
        });

        Schema::create('payment_cheque_receipt_imgs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('img');
            $table->integer('fk_payment_id')->unsigned();
            $table->foreign('fk_payment_id')->references('id')->on('supplier_payment');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('supplier');
    }
}
