@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-4 order-md-2 mb-4">
            <h4 class="d-flex justify-content-between align-items-center mb-3">
                <span class="text-muted">Last post titles</span>
                <span class="badge badge-secondary badge-pill">{{count($posts)}}</span>
            </h4>
            
            <ul class="list-group mb-3">
            @foreach($posts as $post)
                <li class="list-group-item d-flex justify-content-between lh-condensed">
                    <div>
                        <h6 class="my-0">{{$post->title }}</h6>
                        <small class="text-muted">{{$post->created_at->format('d M Y')}}</small>
                    </div>
                    <span class="@if($post->rating >= 0) text-success @else text-danger @endif">{{$post->rating}}</span>
                </li>
            @endforeach
            </ul>
        </div>
          
        <div class="col-md-8 order-md-1">
            <h4 class="mb-3">Post content</h4>
            {{ Form::open(array('action'=>'PostController@store', 'enctype'=>'multipart/form-data', 'method'=>'post')) }}
                <div class="row">
                    <div class="col-md-6 mb-3">
                        {{ Form::label('title', 'Title') }}
                        {{ Form::text('title', null, ['class' => 'form-control']) }}
                    </div>
                    <div class="col-md-6 mb-3">
                        {{ Form::label('type', 'Post Type') }}
                        {{ Form::select('type',[
                             ''=>'Choose...',
                             'true' => 'Recipe',
                             'false' => 'Common Post',
                        ], null, ['class' => 'form-control']) }}
                    </div>
                </div>
                <div class="mb-3">
                    {{Form::label('short_description', 'Short Description')}} <span class="text-muted">(Optional)</span>
                    {{Form::textarea('short-description', null, ['class' => 'form-control', 'placeholder'=>'Make it short', 'rows'=>'2']) }}                    
                </div>
                
                <div class="mb-3">
                    {{Form::label('description', 'Description')}} <span class="text-muted">(Optional)</span>
                    {{Form::textarea('description', null, ['class' => 'form-control', 'placeholder'=>'Show passion as much as you want here', 'rows'=>'6']) }}                    
                </div>
            <h4 class="mb-3">Access</h4>
                <div class="d-block my-3">
                    <div class="custom-control custom-radio">
                        {{Form::radio('access','public', true, ['class'=>'custom-control-input', 'id'=>'public'])}}
                        {{Form::label('public', 'Public access',['class'=>'custom-control-label'])}}
                    </div>
                    <div class="custom-control custom-radio">
                        {{Form::radio('access','follower', false, ['class'=>'custom-control-input', 'id'=>'follower'])}}
                        {{Form::label('follower', 'Share only with followers',['class'=>'custom-control-label'])}}
                    </div>
                </div>
            
            <hr class="mb-4">
            {{Form::submit('Create post', ['class'=>'btn btn-primary btn-lg btn-block'])}}
          {{Form::close()}}
        </div>
      </div>

    </div>
@endsection