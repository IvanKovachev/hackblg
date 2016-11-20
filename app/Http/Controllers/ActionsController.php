<?php

namespace App\Http\Controllers;

use App\Goal;
use App\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ActionsController extends Controller
{
    public function doTask(Request $request)
    {
        $task = Task::where('id', $request->task_id)
            ->where('user_id', Auth::user()->id)
        ->first();

        $task->completions++;
        $task->last_completion = date('Y-m-d H:i:s');

        if ($task->completions == $task->target_completions)
            $task->finished_on = date('Y-m-d H:i:s');

        $task->save();

        return $task;
    }

    public function completeGoal(Request $request)
    {
        $goal = Goal::where('id', $request->goal_id)
            ->where('user_id', Auth::user()->id)
            ->first();

        $goal->completed_on = date('Y-m-d H:i:s');

        $goal->save();

        return $goal;
    }

    public function uncompleteGoal(Request $request)
    {
        $goal = Goal::where('id', $request->goal_id)
            ->where('user_id', Auth::user()->id)
            ->first();

        $goal->completed_on = null;

        $goal->save();

        return $goal;
    }

    public function depositMoney(Request $request)
    {
        $goal = Goal::where('id', $request->goal_id)
            ->where('user_id', Auth::user()->id)
            ->first();

        $goal->amount = $goal->amount + $request->amount;

        $goal->save();

        return $goal;
    }

    public function withdrawMoney(Request $request)
    {
        $goal = Goal::where('id', $request->goal_id)
            ->where('user_id', Auth::user()->id)
            ->first();

        $goal->amount = $goal->amount - $request->amount;

        if ($goal->amount < 0) $goal->amount = 0;

        $goal->save();

        return $goal;
    }

    public function undoTask(Request $request)
    {
        $task = Task::where('id', $request->task_id)
            ->where('user_id', Auth::user()->id)
            ->first();

        $task->completions--;
        $task->last_completion = date('Y-m-d H:i:s');
        $task->finished_on = null;

        $task->save();

        return $task;
    }
}
