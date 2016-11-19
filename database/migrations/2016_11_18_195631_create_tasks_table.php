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
            $table->boolean('is_habit')->default(0);
            $table->string('title');
            $table->string('description');
            $table->json('checklist')->nullable();
//            $table->boolean('is_recurring');
//            $table->tinyInteger('recurring_period')->nullable(); // 1, 2, 3, 4, 5, 6
//            $table->tinyInteger('recurring_type')->nullable(); // month or week or day
//            $table->tinyInteger('recurring_on_day')->nullable(); // day of month or week
            $table->integer('target_completions');
            $table->integer('completions')->unsigned()->default(0);
            $table->boolean('can_expire')->default(0);
            $table->dateTime('last_completion')->nullable();
            $table->dateTime('finished_on')->nullable();
            $table->timestamps();

            $table->index('user_id');
            $table->index('is_habit');
            $table->index('finished_on');
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
