@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-default">
                    <div class="panel-heading">Tasks</div>

                    <div class="panel-body">
                        <form method="POST" action="/tasks">
                            {{ csrf_field() }}
                            <div class="form-group">
                                <label for="title">Title</label>
                                <input type="text" class="form-control" name="title" id="title" value="{{$request->title??''}}">
                            </div>

                            <div class="form-group">
                                <label for="description">Description</label>
                                <input type="text" class="form-control" id="description" name="description" value="{{$request->description??''}}">
                            </div>

                            <div class="form-group">
                                <label for="target_completions">Target completions</label>
                                <input type="number" class="form-control" id="target_completions" name="target_completions" value="{{$request->target_completions??''}}">
                            </div>
                            <button type="submit" class="btn btn-default">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
