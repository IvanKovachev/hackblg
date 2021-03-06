<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExpensesHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('expenses_history', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->integer('income_stream_id')->unsigned()->nullable();
            $table->string('title');
            $table->string('description');
            $table->boolean('is_recurring');
            $table->date('expense_date');
            $table->timestamps();

            $table->index('user_id');
            $table->index('expense_date');
            $table->index('income_stream_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('expenses_history');
    }
}
