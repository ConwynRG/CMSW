@extends('layouts.app')

@section('content')
<script>

function checkAddButtonState(num){
    var addImageBtn = $('#addImageBtn');
        addImageBtn.toggleClass('btn-outline-info', num < 5);
        addImageBtn.toggleClass('btn-danger', num >= 5);
        if(num >= 5)
            addImageBtn.html('Max image count reached');
        else
            addImageBtn.html('Add extra image');
}

$(document).ready(function(){
    $('#image-count').val(1);
        
    $("#imageSection").on("change",'.custom-file-input', function() {
        var fileName = $(this).val().split("\\").pop();
        $(this).siblings(".custom-file-label").html(fileName.substr(0,20)); 
    });

    $('#addImageBtn').on('click',function(){
       var num = Number($('#image-count').val());
       if(num >= 5)
           return;
       num++;
       $('#image-count').val(num);
       var string = `<div class="mb-3 card bg-light" id="card`+num+`">
                <div class="card-header bg-info text-light">
                    <span class="card-title">Image#`+num+`</span>
                    <button type="button" id="deleteImageBtn`+num+`" class="btn btn-danger btn-sm d-block m-auto float-right text-white font-weight-bold deleteImageBtn">Delete Image</button>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-2 ml-3 mt-4 pt-2 mb-3 custom-control custom-radio">
                            {{Form::radio('imgMain','`+num+`',false, ['class'=>'custom-control-input','id'=>'imgRadio`+num+`']) }}
                            {{Form::label('imgRadio`+num+`', 'Main Image', ['class'=>'custom-control-label']) }}
                        </div>
                        <div class="col-md-5 mb-3">
                            {{ Form::label('imgTitle`+num+`', 'Image Title') }}
                            {{ Form::text('imgTitle`+num+`', null, ['class' => 'form-control'.($errors->has('imgTitle`+num+`') ? ' is-invalid' : '')]) }}
                            @if ($errors->has('imgTitle`+num+`'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('imgTitle`+num+`') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="col-md-4 mb-3">     
                            {{Form::label('imgFile`+num+`', 'File Image') }}
                            <div class="input-group">
                                {{Form::file('imgFile`+num+`', ['class'=>'custom-file-input', 'id'=>'imgFile`+num+`']) }}
                                {{Form::label('imgFile`+num+`', 'Choose file...', ['class'=>'custom-file-label'.($errors->has('imgFile`+num+`') ? ' is-invalid' : '')]) }}
                                @if ($errors->has('imgFile`+num+`'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('imgFile`+num+`') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        {{Form::label('image-description`+num+`', 'Image Description')}} <span class="text-muted">(Optional)</span>
                        {{Form::textarea('image-description`+num+`', null, ['class' => 'form-control'.($errors->has('image-description`+num+`') ? ' is-invalid' : ''), 'rows'=>'2']) }}                    
                        @if ($errors->has('image-description`+num+`'))
                            <span class="invalid-feedback">
                                <strong>{{ $errors->first('image-description`+num+`') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>
            </div>`;
        $('#imageSection').append(string);
        checkAddButtonState(num);
    });

    $('#imageSection').on('click','.deleteImageBtn', function(){
        var deletedString = $(this).attr('id');
        var deletedNum = Number(deletedString[deletedString.length-1]);
        $('#card'+deletedNum).remove();
        var totalImages = Number($('#image-count').val());
        for(var i = deletedNum + 1; i <= totalImages; i++){
            console.log('#card'+i+' card-title');
            $('#card'+i+' .card-title').html('Image#'+(i-1));
            $('#card'+i).attr('id','card'+(i-1));
            $('#deleteImageBtn'+i).attr('id','deleteImageBtn'+(i-1));
            $('.custom-radio input[value="'+i+'"]').attr('value',(i-1));
            $('input#imgRadio'+i).attr('name', 'imgRadio'+(i-1));
            $('input#imgRadio'+i).attr('id', 'imgRadio'+(i-1));
            $('label[for="imgRadio'+i+'"]').attr('for','imgRadio'+(i-1));
            $('label[for="imgTitle'+i+'"]').attr('for','imgTitle'+(i-1));
            $('#imgTitle'+i).attr('name','imgTitle'+(i-1));
            $('#imgTitle'+i).attr('id','imgTitle'+(i-1));
            $('#imgFile'+i).attr('name','imgFile'+(i-1));
            $('#imgFile'+i).attr('id','imgFile'+(i-1));
            $('label[for="imgFile'+i+'"]').attr('for','imgFile'+(i-1));
            $('label[for="image-description'+i+'"]').attr('for','image-description'+(i-1));
            $('#image-description'+i).attr('name','image-description'+(i-1));
            $('#image-description'+i).attr('id','image-description'+(i-1));
        }
        totalImages--;
        $('#image-count').val(totalImages);
        checkAddButtonState(totalImages);
    });
});
</script>
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
                        {{ Form::text('title', null, ['class' => 'form-control'.($errors->has('title') ? ' is-invalid' : '')]) }}
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
                        ], null, ['class' => 'form-control'.($errors->has('type') ? ' is-invalid' : '')]) }}
                        @if ($errors->has('type'))
                            <span class="invalid-feedback">
                                <strong>{{ $errors->first('type') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>
                <div class="mb-3">
                    {{Form::label('short-description', 'Short Description')}} <span class="text-muted">(Optional)</span>
                    {{Form::textarea('short-description', null, ['class' => 'form-control'.($errors->has('short-description') ? ' is-invalid' : ''), 'placeholder'=>'Make it short', 'rows'=>'2']) }}                    
                    @if ($errors->has('short-description'))
                        <span class="invalid-feedback">
                            <strong>{{ $errors->first('short-description') }}</strong>
                        </span>
                    @endif
                </div>
                
                <div class="mb-3">
                    {{Form::label('description', 'Description')}} <span class="text-muted">(Optional)</span>
                    {{Form::textarea('description', null, ['class' => 'form-control'.($errors->has('description') ? ' is-invalid' : ''), 'placeholder'=>'Show passion as much as you want here', 'rows'=>'6']) }}                    
                    @if ($errors->has('description'))
                        <span class="invalid-feedback">
                            <strong>{{ $errors->first('description') }}</strong>
                        </span>
                    @endif
                </div>
            
                <h4 class="mb-3">Images</h4>
                <div id="imageSection">
                    {{ Form::hidden('image-count', '1', ['id'=>'image-count']) }}
                    <div class="mb-3 card bg-light">
                        <div class="card-header bg-info text-light">
                            Image#1
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-2 ml-3 mt-4 pt-2 mb-3 custom-control custom-radio">
                                    {{Form::radio('imgMain','1',true, ['class'=>'custom-control-input','id'=>'imgRadio1']) }}
                                    {{Form::label('imgRadio1', 'Main Image', ['class'=>'custom-control-label']) }}
                                </div>
                                <div class="col-md-5 mb-3">
                                    {{ Form::label('imgTitle1', 'Image Title') }}
                                    {{ Form::text('imgTitle1', null, ['class' => 'form-control'.($errors->has('imgTitle1') ? ' is-invalid' : '')]) }}
                                    @if ($errors->has('imgTitle1'))
                                        <span class="invalid-feedback">
                                            <strong>{{ $errors->first('imgTitle1') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="col-md-4 mb-3">     
                                    {{Form::label('imgFile1', 'File Image') }}
                                    <div class="input-group">
                                        {{Form::file('imgFile1', ['class'=>'custom-file-input'.($errors->has('imgFile1') ? ' is-invalid' : ''), 'id'=>'imgFile1']) }}
                                        {{Form::label('imgFile1', 'Choose file...', ['class'=>'custom-file-label']) }}
                                        @if ($errors->has('imgFile1'))
                                            <span class="invalid-feedback">
                                                <strong>{{ $errors->first('imgFile1') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                {{Form::label('image-description1', 'Image Description')}} <span class="text-muted">(Optional)</span>
                                {{Form::textarea('image-description1', null, ['class' => 'form-control'.($errors->has('image-description1') ? ' is-invalid' : ''), 'rows'=>'2']) }}                    
                                @if ($errors->has('image-description1'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('image-description1') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <button type="button" id="addImageBtn" class="btn btn-outline-info d-block m-auto">Add extra image</button>
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
