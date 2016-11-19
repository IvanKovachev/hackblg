<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Goal extends Model
{
    protected $table = 'goals';

    public function tasks()
    {
        return $this->belongsToMany('App\Task', 'tasks_goals');
    }
}
