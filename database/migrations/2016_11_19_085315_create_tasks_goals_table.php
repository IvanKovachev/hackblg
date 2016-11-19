<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTasksGoalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tasks_goals', function (Blueprint $table) {
            $table->integer('task_id')->unsigned()->nullable();
            $table->foreign('task_id')->references('id')
                ->on('tasks')->onDelete('cascade');
            $table->integer('goal_id')->unsigned()->nullable();
            $table->foreign('goal_id')->references('id')
                ->on('goals')->onDelete('cascade');

            $table->index('task_id');
            $table->index('goal_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tasks_goals');
    }
}
