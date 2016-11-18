<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIncomeStreamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('income_streams', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->string('title');
            $table->string('description');
            $table->float('amount');
            $table->boolean('is_recurring');
            $table->tinyInteger('recurring_period')->nullable(); // 1, 2, 3, 4, 5, 6
            $table->tinyInteger('recurring_type')->nullable(); // month or week or day
            $table->tinyInteger('recurring_on_day')->nullable(); // day of month or week
            $table->date('next_payment_date'); // when is the next day to receive it - can be used for non recurring too - e.g. expected income
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
        Schema::dropIfExists('income_streams');
    }
}
