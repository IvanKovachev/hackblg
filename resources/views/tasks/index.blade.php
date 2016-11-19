@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-default">
                    <div class="panel-heading clearfix">
                        <span>Tasks</span>
                        <a class="pull-right" href="{{url('/tasks/create')}}">New Task</a>
                    </div>

                    <div class="panel-body">
                        <ul id="tasks">
                            @foreach ($tasks ?? [] as $task)
                                <?php
                                    $completionPercent = ($task->completions/$task->target_completions)*100;
                                ?>
                                <li>
                                    <div class="task-item clearfix" data-id="{{$task->id}}">
                                        <div class="task-main clearfix">
                                            <div class="complete-action pull-left">
                                                <span title="Do Task" class="glyphicon do-task action-item glyphicon-plus"></span>
                                                <span title="Undo Task" class="glyphicon undo-task action-item glyphicon-minus"></span>
                                            </div>
                                            <div class="title pull-left" data-toggle="collapse" data-target="#collapse-task-{{$task->id}}">{{$task->title}}</div>
                                        </div>

                                        <div class="progress">
                                            <div class="progress-bar" role="progressbar"  style="width: {{$completionPercent}}%;">
                                                {{$task->completions . '/' . $task->target_completions}}
                                            </div>
                                        </div>

                                        <div class="collapse" id="collapse-task-{{$task->id}}">
                                            <div class="well">
                                                {{$task->description}}
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
