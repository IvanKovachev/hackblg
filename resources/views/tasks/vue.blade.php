@extends('layouts.app')

@section('content')
    <div class="container" id="app-tasks">
        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-default">
                    <div class="panel-heading clearfix">
                        <span>Tasks</span>
                        <a class="pull-right" href="{{url('/tasks/create')}}">New Task</a>
                    </div>

                    <div class="panel-body">
                        <ul id="tasks" v-for="task in tasks">
                                <li>
                                    <div class="task-item clearfix" :data-id="task.id">
                                        <div class="task-main clearfix">
                                            <div class="complete-action pull-left">
                                                <span title="Do Task" v-on:click="doTask" class="glyphicon do-task action-item glyphicon-plus"></span>
                                                <span title="Undo Task" v-on:click="undoTask" class="glyphicon undo-task action-item glyphicon-minus"></span>
                                            </div>
                                            <div class="title pull-left" data-toggle="collapse" :data-target="'#collapse-task-'+ task.id">@{{task.title}}</div>
                                        </div>

                                        <div class="progress">
                                            <div class="progress-bar" role="progressbar"  :style="'width: ' + ((task.completions/task.target_completions)*100) + '%;'">
                                                @{{task.completions + '/' + task.target_completions}}
                                            </div>
                                        </div>

                                        <div class="collapse" :id="'collapse-task-' + task.id">
                                            <div class="well">
                                                @{{task.description}}
                                            </div>
                                        </div>
                                    </div>
                                </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
