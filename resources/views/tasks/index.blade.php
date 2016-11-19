@extends('layouts.app')

@section('content')
    <div class="container" id="app-tasks">
        <div class="row" v-if="loaded">
            <div class="col-sm-12">
                <div class="panel panel-default">
                    <div class="panel-heading clearfix">
                        <span>Tasks</span>
                        <a v-if="!addActive" class="pull-right" v-on:click="toggleAdd">New Task</a>
                        <div v-if="addActive">
                            <div class="form-group">
                                <label for="title">Title</label>
                                <input type="text" class="form-control" v-model="newTask.title" name="title" id="title">
                            </div>

                            <div class="form-group">
                                <label for="description">Description</label>
                                <textarea class="form-control" v-model="newTask.description" id="description" name="description" ></textarea>
                            </div>

                            <div class="form-group">
                                <label for="target_completions">Target completions</label>
                                <input type="number" class="form-control" v-model="newTask.target_completions" id="target_completions" name="target_completions">
                            </div>
                            <div class="buttons">
                                <button class="btn btn-primary pull-right" v-on:click="addNew">save</button>
                                <button class="btn btn-default pull-right" v-on:click="cancelAdd">cancel</button>
                            </div>
                        </div>
                    </div>

                    <div class="panel-body">
                        <ul id="tasks">
                                <li v-for="(task, index) in tasks" class="task-item">
                                    <div class="clearfix">
                                        <div class="task-main clearfix">
                                            <div class="complete-action pull-left">
                                                <span title="Do Task" v-on:click="doTask(index)" class="glyphicon do-task action-item glyphicon-plus"></span>
                                                <span title="Undo Task" v-on:click="undoTask(index)" class="glyphicon undo-task action-item glyphicon-minus"></span>
                                            </div>
                                            <div v-if="!task.edit" class="title pull-left" data-toggle="collapse" :data-target="'#collapse-task-'+ task.id">@{{task.title}}</div>
                                            <div v-if="task.edit" class="title pull-left form-group">

                                                <input type="text" class="form-control" v-model="tasks[index].title">
                                            </div>
                                            <div class="pull-right edit" v-on:click="toggleEdit(index)">
                                                <span class="glyphicon glyphicon-pencil"></span>
                                            </div>
                                        </div>

                                        <div class="progress">
                                            <div class="progress-bar" role="progressbar"  :style="'width: ' + ((task.completions/task.target_completions)*100) + '%;'">
                                                @{{task.completions + '/' + task.target_completions}}
                                            </div>
                                        </div>

                                        <div :class="'collapse' + (task.edit?'in':'')" :id="'collapse-task-' + task.id">

                                            <div v-if="!task.edit">@{{task.description}}</div>
                                            <div v-if="task.edit" class="task-edit form-group">
                                                <div class="delete"><span class="glyphicon glyphicon-trash"></span></div>
                                                <textarea class="form-control" v-model="tasks[index].description"></textarea>
                                                <label>Target Completions</label>
                                                <input class="form-control" type="number" v-model="tasks[index].target_completions">

                                                <div class="buttons clearfix">
                                                    <button class="btn btn-primary pull-right" v-on:click="saveTask(index, $event)">Save</button>
                                                    <button class="btn btn-default pull-right" v-on:click="toggleEdit(index)">Cancel</button>
                                                </div>
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
