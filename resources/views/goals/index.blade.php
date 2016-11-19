@extends('layouts.app')

@section('content')
    <div class="container hide" id="app-main">
        <div class="row" v-if="loaded">
            <div class="col-sm-12">
                <goals></goals>
            </div>
        </div>
    </div>
@endsection
