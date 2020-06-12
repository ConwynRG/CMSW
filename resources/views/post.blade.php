@extends('layouts.app')

@section('content')
<script>
$(document).ready(function () {
    $("#comment-create-section").on('click', '#send-comment-btn', function (e) {
        console.log('hello');
        var url = "{{ action('CommentController@addComment') }}";
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        var post_id = {{$post->id}};
        var user_id = {{Auth::id()}};
        var comment_text = $('#comment-area').val();
        if($('#noCommentMessage').length){
            $('#noCommentMessage').remove();
        }
        $('#comment-area').val('');
        console.log(comment_text);
        $.ajax({
            type: "POST",
            url: url,
            data: { post_id: post_id, user_id: user_id, comment_text: comment_text, _token: CSRF_TOKEN },
            success: function (data) {
                console.log(data['comment_text']);
                $('#comment-section').append(`<div class="card">
                        <div class="card-body clearfix">
                            <img width="70" height="70" class="img-thumbnail rounded-circle float-left ml-2 mr-3" src="{{ url('uploads/'.Auth::user()->avatar_filename) }}" alt="Avatar image">

                            <p class="comment-post-content lead">`+data['comment_text']+`</p>
                            <p class="comment-post-meta"> posted at `+ data['date']+` by 
                            <a href="{{url('page/'.Auth::user()->page->id)}}">{{Auth::user()->name}}</a></p>
                        </div>
                    </div>
                    <hr>`);    
            },
            error: function (data) {
                console.log('Error:', data);
            }
        });
    })
});
</script>
<main role="main">
    <div class="container px-lg-5">  
        <div class="px-3 pt-3 pt-md-5 pb-md-1 mx-auto text-center">
            <h1 class="display-4 mb-3">{{$post->title}}</h1>
            <p class="lead">{{$post->short_description}}</p>
        </div>
        @if($post->description != null)
            <hr>
            <div class="blog-post">
                <h2 class="blog-post-title">Main Description</h2>
                <p class="blog-post-meta">{{ $post->created_at->format('M d, Y')}} by <a href="{{url('page',$post->page_id)}}">{{$post->user->name}}</a></p>

                <p>{{$post->description}}</p>
            </div>
        @endif
        <hr>
        @for($i = 0; $i < count($images); $i++)
            <div class="row">
                @if($i % 2 == 0)
                    <div class="col-md-7 m-auto">
                        <h2 class="">{{$images[$i]->title}}</h2>
                        <p class="lead text-justify">{{$images[$i]->description}}</p>
                    </div>
                    <div class="col-md-5">
                        <img class="img-fluid rounded m-auto" src="{{url("uploads/".$images[$i]->filename)}}" alt="Generic placeholder image">
                    </div>
                @else
                    <div class="col-md-7 order-md-2 m-auto ">
                        <h2 class="text-right">{{$images[$i]->title}}</h2>
                        <p class="lead first-line-align-right text-justify">{{$images[$i]->description}}</p>
                    </div>
                    <div class="col-md-5 order-md-1">
                        <img class="img-fluid rounded m-auto" src="{{url("uploads/".$images[$i]->filename)}}" alt="Generic placeholder image">
                    </div>
                @endif
            </div>
            <hr>
        @endfor
        <div class="lead mx-md-5 px-md-5 my-auto clearfix">
            <div class="float-left align-middle my-2 w-50 text-center">
                <div class="mb-1">Post Rating :</div>
                <span class="@if($post->rating >= 0)text-success @else text-danger @endif">{{$post->rating}}</span>
            </div>
            <div class="float-right w-50 my-2 text-center">
                <span class="">Review this post:</span><br>
                <button class="btn btn-outline-success w-25" style="min-width: 80px;">Like</button> 
                <button class="btn btn-outline-danger w-25" style="min-width: 80px;">Dislike</button> 
            </div>
        </div>
        <hr>
        <div class="blog-post">
            <h2 class="comment-post-title">Comment Section</h2>
            <div id="comment-section">
                @if(count($comments)==0)
                <div id="noCommentMessage" class="lead mt-2 mb-5">Leave first comment about this post!</div>
                @endif
                @foreach($comments as $comment)
                    <div class="card">
                        <div class="card-body clearfix">
                            <img width="70" height="70" class="img-thumbnail rounded-circle float-left ml-2 mr-3" src="{{ url('uploads/'.$comment->user->avatar_filename) }}" alt="Avatar image">

                            <p class="comment-post-content lead">{{$comment->comment_text}}</p>
                            <p class="comment-post-meta"> posted at {{ $comment->created_at->format('M d, Y')}} ({{ Carbon\Carbon::now()->diffInHours($comment->created_at)}} hour(-s) {{ Carbon\Carbon::now()->diffInMinutes($comment->created_at) % 60}} minute(-s) ago) by <a href="{{url('page/'.$comment->post->page_id)}}">{{$comment->user->name}}</a></p>
                        </div>
                    </div>
                    <hr>
                @endforeach
            </div>
            @if(Auth::check())
            <div id="comment-create-section">
                <div class="form-group">
                    <label for="comment-area" class="lead">Leave your comment here</label>
                    <textarea class="form-control" id="comment-area" rows="4"></textarea>
                </div>
                <button  id="send-comment-btn" class="btn btn-primary btn-lg w-50 d-block m-auto">Send comment</button>
             </div>
            @endif
        </div>
        <footer>
            <p>
                <a href="#">Back to top</a>
            </p>
        </footer>
    </div>
</main>

<style>
    p.first-line-align-right:first-line{
        text-align: right;
    }
    .blog-post {
      margin-bottom: 4rem;
    }
    .blog-post-title {
      margin-bottom: .25rem;
      font-size: 2.5rem;
    }
    .blog-post-meta {
      margin-bottom: 1.25rem;
      color: #999;
    }
    .comment-post-title{
        font-size: 2.5rem;
        margin-bottom: 1.25rem;
    }
    .comment-post-content{
        margin-bottom: .2rem;
    }
    .comment-post-meta{
        color: #999;
        margin-bottom: 0px;
    }

</style>
@endsection