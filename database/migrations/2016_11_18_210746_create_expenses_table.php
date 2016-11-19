<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExpensesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('expenses', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->string('title');
            $table->string('description');
            $table->float('amount');
            $table->boolean('is_recurring');
            $table->tinyInteger('recurring_period')->nullable(); // 1, 2, 3, 4, 5, 6
            $table->tinyInteger('recurring_type')->nullable(); // month or week or day
            $table->tinyInteger('recurring_on_day')->nullable(); // day of month or week
            $table->date('next_expense_date'); // when is the next day to expense it - can be used for non recurring too - e.g. expected expense
            $table->timestamps();

            $table->index('user_id');
            $table->index('next_expense_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('expenses');
    }
}
