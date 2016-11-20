<?php

namespace App\Http\Controllers;

use App\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;

class TasksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('tasks.index');
    }

    /**
     * Return all (non) completed tasks of the user
     *
     * @return Task collection
     */
    public function all($filter = 'all')
    {
        $taskQuery = Task::with('goals')->where('user_id', Auth::user()->id);

        if ($filter == 'active') {
            $taskQuery->whereNull('finished_on');
        } else if ($filter == 'completed') {
            $taskQuery->whereNotNull('finished_on');
        }

        $taskQuery->orderBy('created_at', 'desc');

        $tasks = $taskQuery->get();

        return $tasks;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('tasks.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
//        dd($request);
        $task = new Task();

        $task->user_id = Auth::user()->id;
        $task->title = $request->title;
        $task->description = $request->description;
//        $task->is_recurring = $request->recurring_type>0 ? 1:0;
//        $task->recurring_period = $request->recurring_period;
//        $task->recurring_on_day = $request->recurring_on_day;
//        $task->recurring_type = $request->recurring_type;
        $task->target_completions = $request->target_completions;

        $task->save();

        return Task::with('goals')->find($task->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
//        dd($request);
        $task = Task::where('id', $request->id)
            ->where('user_id', Auth::user()->id)
            ->first();

        $task->target_completions = $request->target_completions;
        $task->title = $request->title;
        $task->description = $request->description;

        $task->save();

        return $task;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $task = Task::where('id', $id)
            ->where('user_id', Auth::user()->id)
            ->delete();
    }
}
