<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGoalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('goals', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->integer('points')->unsigned();
            $table->string('title');
            $table->string('description');
            $table->tinyInteger('difficulty');
            $table->boolean('is_money_saving');
            $table->dateTime('completed_on');
            $table->timestamps();

            $table->index('user_id');
            $table->index('is_money_saving');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('goals');
    }
}
