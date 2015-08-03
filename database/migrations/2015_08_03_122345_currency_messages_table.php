<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CurrencyMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('currency_messages', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->string('currency_from');
            $table->string('currency_to');
            $table->float('rate');
            $table->float('amount_sell');
            $table->float('amount_buy');
            $table->string('country_origin');
            $table->dateTime('time_placed');
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
        Schema::dropIfExists('currency_messages');
    }
}
