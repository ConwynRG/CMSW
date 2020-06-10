@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-18">
            <div class="jumbotron p-3 p-md-5 text-white rounded bg-success">
                <div class="col-md-8 px-0">
                    <h1 class="display-4 font-italic">Cooking Management Social Web</h1>
                    <p class="lead mb-0"><a class="text-white font-weight-bold">The place where you can share your cooking experience or learn something new</a></p>
                </div>
            </div>
            <div class="card">
                <div class="card-header bg-secondary">
                    <h3 class="pb-2 mb-2 font-italic border-bottom d-flex justify-content-center font-weight-bold text-white">
                        Latest posts
                    </h3>
                </div>
                
                <div class="card-body">
                    @for($i = 0; $i < count($posts); $i++)
                        @if(($i % 2) == 0)
                            <div class="row mb-2">
                        @endif
                        
                            <div class="col-md-6">
                                <div class="card flex-md-row mb-4 box-shadow h-md-250" style="border: 1px solid @if($posts[$i]->isRecipe) #3CB371 @else #6495ED @endif; border-left-width: 10px;">
                                    <div class="card-body d-flex flex-column align-items-start half-width">
                                        @if($posts[$i]->isRecipe)
                                            <strong class="d-inline-block mb-2 text-success">
                                                Recipe @if($posts[$i]->isPublic) (Public) @endif
                                            </strong>
                                        @else
                                            <strong class="d-inline-block mb-2 text-primary">
                                                Result sharing @if($posts[$i]->isPublic) (Public) @endif
                                            </strong>
                                        @endif
                                        <h3 class="mb-2">
                                            <a class="text-dark" href="{{ url('post',$posts[$i]->id)}}">{{ $posts[$i]->title }}</a>
                                        </h3>
                                        <div class="mb-1 text-muted">{{ $posts[$i]->created_at->format('d M')}}</div>
                                        <p class="card-text mb-auto"> {{ $posts[$i]->short_description }}</p>
                                        <p class=""card-text mb-auto">Author: <a href="{{url('page',$posts[$i]->page_id)}}">{{ $posts[$i]->user->name }}</a></p>
                                    </div>
                                    <a class="half-width" href="{{url('post',$posts[$i]->id )}}">
                                    <img class="card-img-right img-thumbnail flex-auto d-none d-md-block cover-img" src="{{ url('uploads/'.(\App\Image::find($posts[$i]->mainImage_id))->filename) }}" alt="Post image">
                                    </a>
                                </div>
                            </div>
                        @if(($i % 2) == 1)
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
    
    .cover-img{
        width:100%;
        object-fit: cover;
    }
</style>