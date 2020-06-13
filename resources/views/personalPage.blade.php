@extends('layouts.app')

@section('content')
<main role="main" class="container">
    <div class="row">
        <aside class="col-md-4 blog-sidebar">
            <div>
                <img class="img-thumbnail rounded-circle centerInDiv"  style="width:250px; height:250px; object-fit: cover;" src="{{ url('uploads/'.$page->user->avatar_filename) }}" alt="Avatar image">
            </div>
            @if(Auth::check() && $page->user->id != Auth::id())
            <div class="pl-4 pr-4 m-3 bg-light rounded">
                <input type="button" class="btn btn-primary btn-lg btn-block" value="{{__('messages.follow_user')}}">
                <input type="button" class="btn btn-outline-danger btn-lg btn-block" value="{{__('messages.block_user')}}">
            </div>
            @endif
            <div class="pl-4 mb-3 bg-light rounded lead">
                <h3 class="font-italic mt-3">{{__('messages.about')}}</h3>
                <p class="mb-0">{{__('messages.username')}}: {{$page->user->name}}</p>
                <p class="mb-3">{{__('messages.email')}}: {{$page->user->email}}</p>
                <p class="mb-3">{{ $page->user->about_me }}</p>
            </div>
            
            
        </aside>  
          
        <div class="col-md-8 blog-main">
            @if(Auth::id() == $page->user_id)
            <a type="button" class="btn btn-sm btn-outline-success float-right" href="{{ url('post/create') }}">{{__('messages.create_new_post')}}</a>
            @endif
            <h3 class="pb-3 mb-4 font-italic border-bottom">
                {{__('messages.latest_user_posts')}}
            </h3>
            
            @if(count($posts) == 0)
                <div class="blog-post lead">
                    {{__('messages.user_did_not_post')}}
                </div>
            @endif
            @foreach($posts as $post)
                @if(Auth::check() || (!Auth::check() && $post->isPublic) || (Auth::id() && $post->user_id))
                <div class="blog-post">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-10">
                                <div class="card mb-4 box-shadow">
                                    <div class="card-body pb-1">
                                        <h2 class="blog-post-title">{{ $post->title }}</h2>
                                        <p class="blog-post-meta">{{ $post->created_at->format('M d, Y') }} {{__('messages.created_by')}} <a href="#"> {{ $post->user->name }}</a></p>
                                    </div>
                                    <img class="pt-0 card-img-top" style="height:250px; object-fit:cover; object-position: 50% 15%" src="{{  url('uploads/'.(\App\Image::find($post->mainImage_id))->filename) }}" alt="Card image cap">
                                    <div class="card-body">
                                        <p class="card-text"> {{ $post->short_description }} </p>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="btn-group">
                                                <a type="button" class="btn btn-sm btn-outline-primary" href="{{url('post',$post->id)}}">{{__('messages.view_post')}}</a>
                                                @if(Auth::id() == $page->user_id)
                                                    <a type="button" class="btn btn-sm btn-outline-success" href="{{url('post/'.$post->id.'/edit')}}">{{__('messages.edit_post')}}</a>
                                                @endif
                                                @if(Auth::id() == $page->user_id || Auth::user()->isAdmin)
                                                    {{Form::open(array('action'=>['PostController@destroy',$post->id], 'method'=>'delete', 'class'=>'m-0'))}}
                                                        <input type="submit" class="btn btn-sm btn-outline-danger" style="margin-left:-1px; border-top-left-radius:0px; border-bottom-left-radius:0px;" value="{{__('messages.delete_post')}}" >
                                                    {{Form::close() }}
                                                @endif
                                            </div>
                                            <small class="text-muted">{{$timeDif[$post->id]}}</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            @endforeach
        </div>
      </div>
    </main>
@endsection

<style>
    img.centerInDiv{
        display:block;
        margin:auto;
    }
</style>