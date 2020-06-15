@extends('layouts.app')

@section('content')
<script>
function updatePosts(){
    var sortPopular = $("#popular_posts").is(':checked') ? '1' : '0';
    var searchText =  $("#search-input").val();
    var url = "{{ action('HomeController@sortPosts') }}";
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
            type: "POST",
            url: url,
            data: { searchText: searchText, sortPopular: sortPopular, _token: CSRF_TOKEN },
            success: function (data) {                
                $('#post-section').html('');
                var post = '';
                const monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun",
                        "Jul", "Aug", "Sep", "Oct", "Nov", "De"
                    ];
                
                for(var i = 0; i < data['posts'].length; i++){
                    var imageUrl = 'uploads/'+data['images'][data['posts'][i].mainImage_id].filename;
                    var pageUrl = 'page/'+data['posts'][i].page_id;
                    var postUrl = 'post/'+data['posts'][i].id;
                    var deleteUrl = 'post/'+data['posts'][i].id+'/delete';
                    var creationDate = new Date(Date.parse(data['posts'][i].created_at));
                    
                    if(i % 2 === 0){
                        post += `<div class="row mb-2">`;
                    }
                    
                    post += `<div class="col-md-6 mx-auto">
                                <div class="card flex-md-row mb-4 box-shadow h-md-250" style="border: 1px solid`; if(data['posts'][i].isRecipe){ post+=`#3CB371;`;}else{ post += `#6495ED;`;} post += `border-left-width: 10px; min-height: 300px">
                                    <div class="card-body d-flex flex-column align-items-start half-width">`;
                                        if(data['posts'][i].isRecipe){
                                         post +=`<strong class="d-inline-block mb-2 text-success">
                                                {{ __('messages.recipe')}}`; 
                                                if(data['posts'][i].isPublic){
                                                    post +=`({{__('messages.public')}})`;
                                                }
                                                post += `</strong>`;
                                                   
                                        }else{
                                        post +=`<strong class="d-inline-block mb-2 text-primary">
                                                {{ __('messages.common_post') }}`; 
                                                if(data['posts'][i].isPublic){
                                                    post +=`({{__('messages.public')}})`;
                                                }
                                                post += `</strong>`;
                                                
                                        }
                                        post +=`<h3 class="mb-2">
                                            <a class="text-dark" href="{{ url('`+postUrl+`')}}">`+data['posts'][i].title+`</a>
                                        </h3>
                                        <div class="mb-1 text-muted">`+(creationDate.getDate())+' '+monthNames[creationDate.getMonth()]+`</div>
                                        <p class="card-text mb-auto">`+data['posts'][i].short_description+`</p>
                                        <p class="card-text mt-4 mb-2"> {{ __('messages.author')}}: 
                                            <a href="{{url('`+pageUrl+`')}}">`+data['users'][data['posts'][i].user_id].name+`</a></p>
                                        <p class="card-text mb-2"> {{ __('messages.post_rating')}}: <span class="`; if(data['posts'][i].rating >= 0){ post += `text-success`}else{ post += `text-danger` } post += `">`+data['posts'][i].rating+`</span></p>
                                        @if(Auth::check() && Auth::user()->isAdmin)
                                        <form method="POST" action="{{ url('`+deleteUrl+`') }}" class="m-0">@csrf @method("DELETE")    
                                            <input type="submit" class="btn btn-sm btn-outline-danger" value="{{__('messages.delete_post')}}" >
                                        </form>
                                        @endif
                                    </div>
                                    <a class="half-width" href="{{url('`+postUrl+`')}}">
                                    <img class="card-img-right img-thumbnail d-md-block cover-img" src="{{ url('`+imageUrl+`') }}" alt="Post image">
                                    </a>
                                </div>
                            </div>`;
                    
                    if(i % 2 === 1 || i === data['posts'].length-1){
                        post += `</div>`;
                        $("#post-section").append(post);
                        post = '';
                    }   
                }
            },
            error: function (data) {
                console.log('Error:', data);
            }
        });
}

$(document).ready(function(){
    $("#search-input").keyup(updatePosts);
    $("input[name=sort_radio]").change(updatePosts);
});
</script>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-18">
            <div class="jumbotron p-3 p-md-5 text-white rounded bg-success">
                <div class="col-md-8 px-0">
                    <h1 class="display-4 font-italic"> {{ __('messages.cmsw_title')}}</h1>
                    <p class="lead mb-0"><a class="text-white font-weight-bold"> {{ __('messages.cmsw_underText')  }}</a></p>
                </div>
            </div>
            <div class="card">
                <div class="card-header bg-secondary">
                    <div class="form-group row">
                        <h3 class="text-center my-auto font-italic font-weight-bold text-white col-sm-2">{{__('messages.search')}}</h3>
                        <div class="col-sm-7 my-auto">
                            <input type="text" class="form-control" id="search-input">
                        </div>
                        <div class="col-sm-3 text-white lead">
                            <div class="custom-control custom-radio">
                            {{Form::radio('sort_radio','latest_posts', true, ['class'=>'custom-control-input', 'id'=>'latest_posts'])}}
                            {{Form::label('latest_posts', __('messages.latest_posts'),['class'=>'custom-control-label'])}}
                        </div>
                        <div class="custom-control custom-radio">
                            {{Form::radio('sort_radio','popular_posts', false, ['class'=>'custom-control-input', 'id'=>'popular_posts'])}}
                            {{Form::label('popular_posts', __('messages.popular_posts'),['class'=>'custom-control-label'])}}
                        </div>
                        </div>
                    </div>
                    
                    
                    <h3 class="pb-2 mt-3 pt-2 mb-0 font-italic border-top d-flex justify-content-center font-weight-bold text-white">
                        {{ __('messages.latest_posts') }}
                    </h3>
                </div>
                
                <div class="card-body" id="post-section">
                    @for($i = 0; $i < count($posts); $i++)
                        @if(($i % 2) == 0)
                            <div class="row mb-2">
                        @endif
                        
                            <div class="col-md-6 mx-auto">
                                <div class="card flex-md-row mb-4 box-shadow h-md-250" style="border: 1px solid @if($posts[$i]->isRecipe) #3CB371 @else #6495ED @endif; border-left-width: 10px; min-height: 300px">
                                    <div class="card-body d-flex flex-column align-items-start half-width">
                                        @if($posts[$i]->isRecipe)
                                            <strong class="d-inline-block mb-2 text-success">
                                                {{ __('messages.recipe')}} @if($posts[$i]->isPublic) ({{__('messages.public')}}) @endif
                                            </strong>
                                        @else
                                            <strong class="d-inline-block mb-2 text-primary">
                                                {{ __('messages.common_post') }} @if($posts[$i]->isPublic) ({{__('messages.public')}}) @endif
                                            </strong>
                                        @endif
                                        <h3 class="mb-2">
                                            <a class="text-dark" href="{{ url('post',$posts[$i]->id)}}">{{ $posts[$i]->title }}</a>
                                        </h3>
                                        <div class="mb-1 text-muted">{{ $posts[$i]->created_at->format('d M')}}</div>
                                        <p class="card-text mb-auto"> {{ $posts[$i]->short_description }}</p>
                                        <p class="card-text mt-4 mb-1"> {{ __('messages.author')}}: <a href="{{url('page',$posts[$i]->page_id)}}">{{ $posts[$i]->user->name }}</a></p>
                                        <p class="card-text mb-2"> {{ __('messages.post_rating')}}: <span class="@if($posts[$i]->rating >= 0)text-success @else text-danger @endif">{{ $posts[$i]->rating }}</span></p>
                                        @if(Auth::check() && Auth::user()->isAdmin)
                                        {{Form::open(array('action'=>['PostController@destroy',$posts[$i]->id], 'method'=>'delete', 'class'=>'m-0'))}}
                                            <input type="submit" class="btn btn-sm btn-outline-danger" value="{{__('messages.delete_post')}}" >
                                        {{Form::close() }}
                                        @endif
                                    </div>
                                    <a class="half-width" href="{{url('post',$posts[$i]->id )}}">
                                    <img class="card-img-right img-thumbnail d-md-block cover-img" src="{{ url('uploads/'.(\App\Image::find($posts[$i]->mainImage_id))->filename) }}" alt="Post image">
                                    </a>
                                </div>
                            </div>
                        @if(($i % 2) == 1 || $i == count($posts)-1)
                        </div>
                        @endif
                    @endfor
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
<style>
    .half-width{
        width:50%;
    }
    
    img.cover-img{
        width:100%;
        height: 100%;
        object-fit: cover;
    }
</style>