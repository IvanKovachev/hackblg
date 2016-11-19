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

    public function all()
    {
        $tasks = Task::where('user_id', Auth::user()->id)
            ->whereNull('finished_on')
            ->get();

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

        return redirect('tasks')->withInput();
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
        //
    }
}
