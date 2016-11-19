<?php

namespace App\Http\Controllers;

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
