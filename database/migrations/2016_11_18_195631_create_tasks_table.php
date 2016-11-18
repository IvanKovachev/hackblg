<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->integer('goal_id')->unsigned()->nullable();
            $table->boolean('is_habit');
            $table->string('title');
            $table->string('description');
            $table->json('checklist')->nullable();
            $table->boolean('is_recurring');
            $table->tinyInteger('recurring_period')->nullable();
            $table->tinyInteger('recurring_start_day')->nullable();
            $table->tinyInteger('difficulty');
            $table->boolean('can_expire');
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
        Schema::dropIfExists('tasks');
    }
}
