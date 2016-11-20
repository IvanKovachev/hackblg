<?php

namespace App\Http\Controllers;

use App\Goal;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

class GoalsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('goals.index');
    }

    /**
     * Return all (non) completed goals of the user
     *
     * @return Goal collection
     */
    public function all($filter = 'all')
    {
        $goalQuery = Goal::with('tasks')->where('user_id', Auth::user()->id);

        if ($filter == 'active') {
            $goalQuery->whereNull('completed_on');
        } else if ($filter == 'completed') {
            $goalQuery->whereNotNull('completed_on');
        }

        $goalQuery->orderBy('created_at', 'desc');

        $goals = $goalQuery->get();

        return $goals;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $goal = new Goal();

        $goal->user_id = Auth::user()->id;
        $goal->title = $request->title;
        $goal->description = $request->description;
        $goal->is_money_saving = $request->is_money_saving;
        $goal->amount = $request->amount;
        $goal->target_amount = $request->target_amount;

        $goal->save();

        if (count($request->tasks) > 0) {
            $tasksToSave = [];

            foreach ($request->tasks as $task) {
                $tasksToSave[] = $task['id'];
            }

            $goal->tasks()->attach($tasksToSave);
        }

        return Goal::with('tasks')->find($goal->id);
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
        $goal = Goal::with('tasks')->where('id', $request->id)
            ->where('user_id', Auth::user()->id)
            ->first();

        $goal->is_money_saving = $request->is_money_saving;

        if ($request->is_money_saving) {
            $goal->amount = $request->amount;
            $goal->target_amount = $request->target_amount;
        } else {
            $goal->amount = 0.0;
            $goal->target_amount = 0.0;
        }

        $goal->title = $request->title;
        $goal->description = $request->description;

        $goal->save();

        if (count($request->tasks) > 0) {
            $tasksToRemove = [];
            $tasksToSave = [];

            foreach ($goal->tasks as $task) {
                $tasksToRemove[] = $task->id;
            }

            $goal->tasks()->detach($tasksToRemove);

            foreach ($request->tasks as $task) {
                $tasksToSave[] = $task['id'];
            }

            $goal->tasks()->attach($tasksToSave);
        }

        return $goal;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $goal = Goal::where('id', $id)
            ->where('user_id', Auth::user()->id)
            ->delete();
    }
}
