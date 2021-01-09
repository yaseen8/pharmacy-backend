<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDailyWagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('daily_wages', function (Blueprint $table) {
            $table->increments('id');
            $table->decimal('amount', 8, 2);
            $table->timestamp('added_on');
            $table->integer('employee_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->foreign('employee_id')->references('id')->on('employees');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('daily_wages');
    }
}
