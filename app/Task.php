<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $table = 'tasks';

    public function goals()
    {
        return $this->belongsToMany('App\Goal', 'tasks_goals');
    }
}
