<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMonthlyRatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('monthly_rates', function (Blueprint $table) {
            $table->increments('id');
            $table->string('currency_from');
            $table->string('currency_to');
            $table->float('avg_rate');
            $table->integer('month');
            $table->integer('year');
            $table->integer('tot_messages');
            $table->float('sum_rate');
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
        Schema::drop('currency_messages');
    }
}
