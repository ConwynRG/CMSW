@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-4 order-md-2 mb-4">
            <h4 class="d-flex justify-content-between align-items-center mb-3">
                <span class="text-muted">Last written comments</span>
                <span class="badge badge-secondary badge-pill">{{count($comments)}}</span>
            </h4>
            
            <ul class="list-group mb-3">
            @foreach($comments as $comment)
                <li class="list-group-item d-flex justify-content-between lh-condensed">
                    <div>
                        <h6 class="my-0">{{$comment->comment_text }}</h6>
                        <small class="text-muted">at {{$comment->created_at->format('d M Y')}} by 
                            <a href="{{ url("page",$comment->user_id)}}">{{$comment->user->name}}</a></small>
                    </div>
                </li>
            @endforeach
            </ul>
        </div>
          
        <div class="col-md-8 order-md-1">
            <h4 class="mb-3">Post content</h4>
            {{ Form::open(array('action'=>['PostController@update',$post->id], 'enctype'=>'multipart/form-data', 'method'=>'post')) }}
                <div class="row">
                    <div class="col-md-6 mb-3">
                        {{ Form::label('title', 'Title') }}
                        {{ Form::text('title', $post->title, ['class' => 'form-control'.($errors->has('title') ? ' is-invalid' : '')]) }}
                         @if ($errors->has('title'))
                            <span class="invalid-feedback">
                                <strong>{{ $errors->first('title') }}</strong>
                            </span>
                        @endif 
                    </div>
                    <div class="col-md-6 mb-3">
                        {{ Form::label('type', 'Post Type') }}
                        {{ Form::select('type',[
                             ''=>'Choose...',
                             'recipe' => 'Recipe',
                             'commonPost' => 'Common Post',
                        ], $post->isRecipe ? 'recipe' : 'commonPost', ['class' => 'form-control'.($errors->has('type') ? ' is-invalid' : '')]) }}
                        @if ($errors->has('type'))
                            <span class="invalid-feedback">
                                <strong>{{ $errors->first('type') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>
                <div class="mb-3">
                    {{Form::label('short-description', 'Short Description')}} <span class="text-muted">(Optional)</span>
                    {{Form::textarea('short-description', $post->short_description, ['class' => 'form-control'.($errors->has('short-description') ? ' is-invalid' : ''), 'placeholder'=>'Make it short', 'rows'=>'2']) }}                    
                    @if ($errors->has('short-description'))
                        <span class="invalid-feedback">
                            <strong>{{ $errors->first('short-description') }}</strong>
                        </span>
                    @endif
                </div>
                
                <div class="mb-3">
                    {{Form::label('description', 'Description')}} <span class="text-muted">(Optional)</span>
                    {{Form::textarea('description', $post->description, ['class' => 'form-control'.($errors->has('description') ? ' is-invalid' : ''), 'placeholder'=>'Show passion as much as you want here', 'rows'=>'6']) }}                    
                    @if ($errors->has('description'))
                        <span class="invalid-feedback">
                            <strong>{{ $errors->first('description') }}</strong>
                        </span>
                    @endif
                </div>
            
                <h4 class="mb-3">Images</h4>
                <div id="imageSection">
                    {{ Form::hidden('image-count', count($images), ['id'=>'image-count']) }}
                    @for($i = 1; $i <= count($images); $i++)
                        <div class="mb-3 card bg-light">
                            <div class="card-header bg-info text-light">
                                Image#1
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-2 ml-3 mt-4 pt-2 mb-3 custom-control custom-radio">
                                        {{Form::radio('imgMain',$i, $images[$i-1]->id == $post->mainImage_id ? true : false, ['class'=>'custom-control-input','id'=>'imgRadio1']) }}
                                        {{Form::label('imgRadio'.$i, 'Main Image', ['class'=>'custom-control-label']) }}
                                    </div>
                                    <div class="col-md-5 mb-3">
                                        {{ Form::label('imgTitle'.$i, 'Image Title') }}
                                        {{ Form::text('imgTitle'.$i, $images[$i-1]->title, ['class' => 'form-control'.($errors->has('imgTitle'.$i) ? ' is-invalid' : '')]) }}
                                        @if ($errors->has('imgTitle'.$i))
                                            <span class="invalid-feedback">
                                                <strong>{{ $errors->first('imgTitle'.$i) }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="col-md-4 mb-3">     
                                        {{Form::label('imgFile'.$i, 'File Image') }}
                                        <div class="input-group">
                                            {{Form::file('imgFile'.$i, ['class'=>'custom-file-input'.($errors->has('imgFile'.$i) ? ' is-invalid' : ''), 'id'=>'imgFile'.$i]) }}
                                            {{Form::label('imgFile'.$i, substr($images[$i-1]->original_filename,0,20), ['class'=>'custom-file-label']) }}
                                            @if ($errors->has('imgFile'.$i))
                                                <span class="invalid-feedback">
                                                    <strong>{{ $errors->first('imgFile'.$i) }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    {{Form::label('image-description'.$i, 'Image Description')}} <span class="text-muted">(Optional)</span>
                                    {{Form::textarea('image-description'.$i, $images[$i-1]->description, ['class' => 'form-control'.($errors->has('image-description'.$i) ? ' is-invalid' : ''), 'rows'=>'2']) }}                    
                                    @if ($errors->has('image-description'.$i))
                                        <span class="invalid-feedback">
                                            <strong>{{ $errors->first('image-description'.$i) }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <img class="img-thumbnail flex-auto d-none d-md-block m-auto" id="imgPicture{{$i}}" style=" width: 75%; object-fit:contain;" src="{{  url('uploads/'.($images[$i-1]->filename)) }}" alt="Post image">
                            </div>
                        </div>
                     @endfor
                    </div>
                <div class="mb-3">
                    <button type="button" id="addImageBtn" class="btn btn-outline-info d-block m-auto">Add extra image</button>
                </div>
                
                <h4 class="mb-3">Access</h4>
                    <div class="d-block my-3">
                        <div class="custom-control custom-radio">
                            {{Form::radio('access','public', $post->isPublic ? true : false, ['class'=>'custom-control-input', 'id'=>'public'])}}
                            {{Form::label('public', 'Public access',['class'=>'custom-control-label'])}}
                        </div>
                        <div class="custom-control custom-radio">
                            {{Form::radio('access','follower', $post->isPublic ? false : true, ['class'=>'custom-control-input', 'id'=>'follower'])}}
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