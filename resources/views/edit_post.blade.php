@extends('layouts.app')

@section('content')
<script>
function checkAddButtonState(num){
    var addImageBtn = $('#addImageBtn');
        addImageBtn.toggleClass('btn-outline-info', num < 5);
        addImageBtn.toggleClass('btn-danger', num >= 5);
        if(num >= 5)
            addImageBtn.html(" {{ __('messages.max_image_count')}}");
        else
            addImageBtn.html("{{ __('messages.add_extra_image') }}");
}

function deleteImageIfNecessary(positionNumber){
    if($('#card'+positionNumber+'[oldImageId]').length){
        var imageId = $('#card'+positionNumber+'[oldImageId]').attr('oldImageId');

        if($('#hiddenImageSection input[value="'+imageId+'"]').length == 0){
            var deleteId = Number($('#hiddenImageSection #image-toDelete-count').val()) + 1;
            $('#hiddenImageSection').append(`{{ Form::hidden('imgToDelete`+deleteId+`', '`+imageId+`') }}`);
            $('#hiddenImageSection #image-toDelete-count').val(deleteId);
            $('#newImage'+positionNumber).val(true);
        }
    }
}

$(document).ready(function(){
   checkAddButtonState({{count($images)}});
   
   $("#imageSection").on("change",'.custom-file-input', function() {
        var fileName = $(this).val().split("\\").pop();
        $(this).siblings(".custom-file-label").html(fileName.substr(0,20));
        
        var idString = $(this).attr('id');
        var id = idString[idString.length-1];
        
        if (this.files && this.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $('#imgPicture'+id)
                    .attr('src', e.target.result)
            };
            reader.readAsDataURL(this.files[0]);
        }
        deleteImageIfNecessary(id);
        
    });

    $('#addImageBtn').on('click',function(){
       var num = Number($('#image-count').val());
       if(num >= 5)
           return;
       num++;
       $('#image-count').val(num);
       var string = `<div class="mb-3 card bg-light" id="card`+num+`">
                <div class="card-header bg-info text-light">
                    <span class="card-title">{{__('messages.image')}}#`+num+`</span>
                    <button type="button" id="deleteImageBtn`+num+`" class="btn btn-danger btn-sm d-block m-auto float-right text-white font-weight-bold deleteImageBtn">{{__('messages.delete_image')}}</button>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-2 ml-3 mt-4 pt-2 mb-3 custom-control custom-radio">
                            {{Form::radio('imgMain','`+num+`',false, ['class'=>'custom-control-input','id'=>'imgRadio`+num+`']) }}
                            {{Form::label('imgRadio`+num+`', __('messages.main_image'), ['class'=>'custom-control-label']) }}
                        </div>
                        <div class="col-md-5 mb-3">
                            {{ Form::label('imgTitle`+num+`', __('messages.image_title')) }}
                            {{ Form::text('imgTitle`+num+`', null, ['class' => 'form-control'.($errors->has('imgTitle`+num+`') ? ' is-invalid' : '')]) }}
                            @if ($errors->has('imgTitle`+num+`'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('imgTitle`+num+`') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="col-md-4 mb-3">     
                            {{Form::label('imgFile`+num+`', __('messages.image_file')) }}
                            <div class="input-group">
                                {{Form::file('imgFile`+num+`', ['class'=>'custom-file-input', 'id'=>'imgFile`+num+`']) }}
                                {{Form::label('imgFile`+num+`', __('messages.choose_file'), ['class'=>'custom-file-label'.($errors->has('imgFile`+num+`') ? ' is-invalid' : '')]) }}
                                @if ($errors->has('imgFile`+num+`'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('imgFile`+num+`') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        {{Form::label('image-description`+num+`', __('messages.image_desc'))}} <span class="text-muted">({{__('messages.optional')}})</span>
                        {{Form::textarea('image-description`+num+`', null, ['class' => 'form-control'.($errors->has('image-description`+num+`') ? ' is-invalid' : ''), 'rows'=>'2']) }}                    
                        @if ($errors->has('image-description`+num+`'))
                            <span class="invalid-feedback">
                                <strong>{{ $errors->first('image-description`+num+`') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>
                <img class="img-thumbnail flex-auto d-none d-md-block m-auto" id="imgPicture`+num+`" style=" width: 75%; object-fit:contain;" src="{{  url('uploads/defaultPostImg.png') }}" alt="Post image">
                {{ Form::hidden('newImage`+num+`', '1',['id'=>'newImage`+num+`']) }}
            </div>`;
        $('#imageSection').append(string);
        checkAddButtonState(num);
    });

    $('#imageSection').on('click','.deleteImageBtn', function(){
        var deletedString = $(this).attr('id');
        var deletedNum = Number(deletedString[deletedString.length-1]);
        deleteImageIfNecessary(deletedNum);
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
            $('#imgPicture'+i).attr('id','imgPicture'+(i-1));
            $('#newImage'+i).attr('id','newImage'+(i-1));
            $('#oldImageId'+i).attr('id','oldImageId'+(i-1));
            
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
                <span class="text-muted">{{__('messages.last_post_titles')}}</span>
                <span class="badge badge-secondary badge-pill">{{count($comments)}}</span>
            </h4>
            
            <ul class="list-group mb-3">
            @foreach($comments as $comment)
                <li class="list-group-item d-flex justify-content-between lh-condensed">
                    <div>
                        <h6 class="my-0">{{$comment->comment_text }}</h6>
                        <small class="text-muted">{{$comment->created_at->format('d M Y')}} {{__('messages.created_by')}} 
                            <a href="{{ url("page",$comment->user_id)}}">{{$comment->user->name}}</a></small>
                    </div>
                </li>
            @endforeach
            </ul>
        </div>
          
        <div class="col-md-8 order-md-1">
            <h4 class="mb-3">{{__('messages.post_content')}}</h4>
            {{ Form::open(array('action'=>['PostController@update',$post->id], 'enctype'=>'multipart/form-data', 'method'=>'PUT')) }}
                <div class="row">
                    <div class="col-md-6 mb-3">
                        {{ Form::label('title', __('messages.title')) }}
                        {{ Form::text('title', $post->title, ['class' => 'form-control'.($errors->has('title') ? ' is-invalid' : '')]) }}
                         @if ($errors->has('title'))
                            <span class="invalid-feedback">
                                <strong>{{ $errors->first('title') }}</strong>
                            </span>
                        @endif 
                    </div>
                    <div class="col-md-6 mb-3">
                        {{ Form::label('type', __('messages.post_type')) }}
                        {{ Form::select('type',[
                             ''=> __('messages.choose'),
                             'recipe' => __('messages.recipe'),
                             'commonPost' => __('messages.common_post'),
                        ], $post->isRecipe ? 'recipe' : 'commonPost', ['class' => 'form-control'.($errors->has('type') ? ' is-invalid' : '')]) }}
                        @if ($errors->has('type'))
                            <span class="invalid-feedback">
                                <strong>{{ $errors->first('type') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>
                <div class="mb-3">
                    {{Form::label('short-description', __('messages.short_desc'))}} <span class="text-muted">({{ __('messages.optional')}})</span>
                    {{Form::textarea('short-description', $post->short_description, ['class' => 'form-control'.($errors->has('short-description') ? ' is-invalid' : ''), 'placeholder'=>__('messages.make_short'), 'rows'=>'2']) }}                    
                    @if ($errors->has('short-description'))
                        <span class="invalid-feedback">
                            <strong>{{ $errors->first('short-description') }}</strong>
                        </span>
                    @endif
                </div>
                
                <div class="mb-3">
                    {{Form::label('description', __('messages.desc'))}} <span class="text-muted">({{__('messages.optional')}})</span>
                    {{Form::textarea('description', $post->description, ['class' => 'form-control'.($errors->has('description') ? ' is-invalid' : ''), 'placeholder'=>__('messages.desc_placeholder'), 'rows'=>'6']) }}                    
                    @if ($errors->has('description'))
                        <span class="invalid-feedback">
                            <strong>{{ $errors->first('description') }}</strong>
                        </span>
                    @endif
                </div>
            
                <h4 class="mb-3">{{__('messages.images')}}</h4>
                <div id="imageSection">
<!----------------- Image Section Starts ----------------------------------------->
                    <div id="hiddenImageSection">
                        {{ Form::hidden('image-count', count($images), ['id'=>'image-count']) }}
                        {{ Form::hidden('image-toDelete-count', 0, ['id'=>'image-toDelete-count']) }}
                    </div>
                    @for($i = 1; $i <= count($images); $i++)
                        <div id="card{{$i}}" class="mb-3 card bg-light" oldImageNumber="{{$i}}" oldImageId="{{$images[$i-1]->id}}">
                            <div class="card-header bg-info text-light">
                                <span class="card-title">{{__('messages.image')}}#{{$i}}</span>
                                @if($i != 1)
                                    <button type="button" id="deleteImageBtn{{$i}}" class="btn btn-danger btn-sm d-block m-auto float-right text-white font-weight-bold deleteImageBtn">{{__('messages.delete_image')}}</button>
                                @endif
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-2 ml-3 mt-4 pt-2 mb-3 custom-control custom-radio">
                                        {{Form::radio('imgMain',$i, $images[$i-1]->id == $post->mainImage_id ? true : false, ['class'=>'custom-control-input','id'=>'imgRadio'.$i]) }}
                                        {{Form::label('imgRadio'.$i, __('messages.main_image'), ['class'=>'custom-control-label']) }}
                                    </div>
                                    <div class="col-md-5 mb-3">
                                        {{ Form::label('imgTitle'.$i, __('messages.image_title')) }}
                                        {{ Form::text('imgTitle'.$i, $images[$i-1]->title, ['class' => 'form-control'.($errors->has('imgTitle'.$i) ? ' is-invalid' : '')]) }}
                                        @if ($errors->has('imgTitle'.$i))
                                            <span class="invalid-feedback">
                                                <strong>{{ $errors->first('imgTitle'.$i) }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="col-md-4 mb-3">     
                                        {{Form::label('imgFile'.$i, __('messages.image_file')) }}
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
                                    {{Form::label('image-description'.$i, __('messages.image_desc'))}} <span class="text-muted">({{__('messages.optional')}})</span>
                                    {{Form::textarea('image-description'.$i, $images[$i-1]->description, ['class' => 'form-control'.($errors->has('image-description'.$i) ? ' is-invalid' : ''), 'rows'=>'2']) }}                    
                                    @if ($errors->has('image-description'.$i))
                                        <span class="invalid-feedback">
                                            <strong>{{ $errors->first('image-description'.$i) }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <img class="img-thumbnail flex-auto d-none d-md-block m-auto" id="imgPicture{{$i}}" style=" width: 75%; object-fit:contain;" src="{{  url('uploads/'.($images[$i-1]->filename)) }}" alt="Post image">
                                {{ Form::hidden('newImage'.$i, '0',['id'=>'newImage'.$i]) }}
                                {{ Form::hidden('oldImageId'.$i, $images[$i-1]->id,['id'=>'oldImageId'.$i]) }}
                            </div>
                        </div>
                     @endfor
<!----------------- Image Section Ends ----------------------------------------->
                    </div>
                <div class="mb-3">
                    <button type="button" id="addImageBtn" class="btn btn-outline-info d-block m-auto">
                        {{ __("messages.add_extra_image") }} 
                    </button>
                </div>
                
                <h4 class="mb-3">{{ __('messages.access') }}</h4>
                    <div class="d-block my-3">
                        <div class="custom-control custom-radio">
                            {{Form::radio('access','public', $post->isPublic ? true : false, ['class'=>'custom-control-input', 'id'=>'public'])}}
                            {{Form::label('public', __('messages.public_access'),['class'=>'custom-control-label'])}}
                        </div>
                        <div class="custom-control custom-radio">
                            {{Form::radio('access','follower', $post->isPublic ? false : true, ['class'=>'custom-control-input', 'id'=>'follower'])}}
                            {{Form::label('follower', __('messages.share_with_followers'),['class'=>'custom-control-label'])}}
                        </div>
                    </div>
            
                <hr class="mb-4">
                <div class="btn-group m-auto" style="width:100%">
                    <a type="button" class="mt-0 btn btn-secondary btn-lg btn-block" href="{{url('page',Auth::id())}}">{{ __('messages.cancel') }}</a>
                    {{Form::submit(__('messages.save_changes'), ['class'=>'mt-0 btn btn-primary btn-lg btn-block'])}}
                </div>
            {{Form::close()}}
        </div>
    </div>
</div>
@endsection