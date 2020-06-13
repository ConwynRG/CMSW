@extends('layouts.app')

@section('content')
<script>
function postRatingUpdate(rating){
    $('#rating-number').html(rating);
    if(rating >= 0){
        $('#rating-number').removeClass('text-danger');
        $('#rating-number').addClass('text-success');
    }else{
        $('#rating-number').removeClass('text-success');
        $('#rating-number').addClass('text-danger');                            
    }
}

$(document).ready(function () {
    //Comment creation
    $("#comment-create-section").on('click', '#send-comment-btn', function (e) {
        var url = "{{ action('CommentController@addComment') }}";
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        var post_id = {{$post->id}};
        @if(Auth::check())
            var user_id = {{Auth::id()}};
        @endif
        var comment_text = $('#comment-area').val();
        if($('#noCommentMessage').length){
            $('#noCommentMessage').remove();
        }
        $('#comment-area').val('');
        $.ajax({
            type: "POST",
            url: url,
            data: { post_id: post_id, user_id: user_id, comment_text: comment_text, _token: CSRF_TOKEN },
            success: function (data) {                
                var avatarLink = "uploads/"+data['user_avatar_filename'];
                var userPage = "page/"+data['user_id'];
                
                $('#comment-section').append(`<div id="card`+data['comment_id']+`" class="card">
                        <div class="card-body clearfix">
                            <img width="70" height="70" class="img-thumbnail rounded-circle float-left ml-2 mr-3" src="{{ url('`+avatarLink+`') }}" alt="Avatar image">
                            <img width="32" height="32" class="img-btn float-right ml-md-2 mb-2" id="delete-comment-btn" comment-id="`+data['comment_id']+`" src="{{ url('uploads/closeCross.png') }}" alt="Delete comment btn">
                                
                            <p class="comment-post-content lead">`+data['comment_text']+`</p>
                            <p class="comment-post-meta"> posted at `+ data['date']+` by 
                            <a href="{{ url('`+userPage+`') }}"> `+data['username']+`</a></p>
                        </div>
                    </div>
                    <hr>`);    
            },
            error: function (data) {
                console.log('Error:', data);
            }
        });
    });//Comment creation ends
    //Comment deletion
    $("#comment-section").on('click', '#delete-comment-btn', function (e) {
        var url = "{{ action('CommentController@deleteComment') }}";
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        var comment_id = $(this).attr('comment-id');
        $.ajax({
            type: "POST",
            url: url,
            data: { comment_id: comment_id, _token: CSRF_TOKEN },
            success: function (data) {
                if(data['isDeleted']){
                    $('#card'+data['comment_id']+' + hr').remove();
                    $('#card'+data['comment_id']).remove();
                }
                },
            error: function (data) {
                console.log('Error:', data);
            }
        });
    });//Comment deletion ends
    
    $('#review-btns').on('click','.review-btn',function(e){
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        var post_id = {{$post->id}};
        @if(Auth::check())
            var user_id = {{Auth::id()}};
        @endif
        var review_value = $(this).attr('review');
        if($(this).hasClass('btn-outline-success') || $(this).hasClass('btn-outline-danger')){
            var url = "{{ action('PostReviewController@addPostReview') }}";
            $.ajax({
                type: "POST",
                url: url,
                data: { post_id: post_id, user_id: user_id, review_value: review_value, _token: CSRF_TOKEN },
                success: function (data) {
                    if(data['review_value']>0){
                        $('#like-btn').removeClass('btn-outline-success');
                        if(!$('#like-btn').hasClass('btn-success'))
                            $('#like-btn').addClass('btn-success');
                        
                        $('#dislike-btn').removeClass('btn-danger');
                        if(!$('#dislike-btn').hasClass('btn-outline-danger'))
                            $('#dislike-btn').addClass('btn-outline-danger');
                    }else{
                        $('#dislike-btn').removeClass('btn-outline-danger');
                        if(!$('#dislike-btn').hasClass('btn-danger'))
                            $('#dislike-btn').addClass('btn-danger');
                        
                        $('#like-btn').removeClass('btn-success');
                        if(!$('#like-btn').addClass('btn-outline-success'))
                            $('#like-btn').addClass('btn-outline-success');
                                                        
                    }
                    
                    postRatingUpdate(data['post_rating']);
                    
                    },
                error: function (data) {
                    console.log('Error:', data);
                }
            });
        }else{
            var url = "{{ action('PostReviewController@nullifyPostReview') }}";
            $.ajax({
                type: "POST",
                url: url,
                data: { post_id: post_id, user_id: user_id, review_value: review_value, _token: CSRF_TOKEN },
                success: function (data) {
                    if(data['review_value']>0){ 
                        $('#like-btn').removeClass('btn-success');
                        if(!$('#like-btn').hasClass('btn-outline-success'))
                            $('#like-btn').addClass('btn-outline-success');
                    }else{
                        $('#dislike-btn').removeClass('btn-danger');
                        if(!$('#dislike-btn').hasClass('btn-outline-danger'))
                            $('#dislike-btn').addClass('btn-outline-danger');
                    }
                    postRatingUpdate(data['post_rating']);
                    
                    },
                error: function (data) {
                    console.log('Error:', data);
                }
            });
        }
    });
});//Document ready ends
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
<!--------RATING SECTION----------------->
        <div class="lead mx-md-5 px-md-5 my-auto clearfix">
            <div class="float-left align-middle my-2 @if(Auth::check()) w-50 @else w-100 @endif text-center">
                <div class="mb-1">Post Rating :</div>
                <span id="rating-number" class="@if($post->rating >= 0)text-success @else text-danger @endif">{{$post->rating}}</span>
            </div>
            @if(Auth::check())
            <div class="float-right w-50 my-2 text-center" id="review-btns">
                <span class="">Review this post:</span><br>
                <button id="like-btn" class="btn @if($review_value > 0) btn-success @else btn-outline-success @endif w-25 review-btn" review="1" style="min-width: 80px;">Like</button> 
                <button id="dislike-btn" class="btn @if($review_value < 0) btn-danger @else btn-outline-danger @endif w-25 review-btn"  review="-1" style="min-width: 80px;">Dislike</button> 
            </div>
            @endif
        </div>
<!-----------RATINS SECTION ENDS------------------->
        <hr>
        <div class="blog-post">
<!------------ COMMENT SECTION -------------->
            <h2 class="comment-post-title">Comment Section</h2>
            <div id="comment-section">
                @if(count($comments)==0)
                <div id="noCommentMessage" class="lead mt-2 mb-5">Leave first comment about this post!</div>
                @endif
                @foreach($comments as $comment)
                    <div id="card{{$comment->id}}" class="card">
                        <span class="d-none" id="comment-id" value="{{$comment->id}}"></span>
                        <div class="card-body clearfix">
                            <img class="img-thumbnail rounded-circle float-left ml-2 mr-3"  style="width:70px; height:70px; object-fit: cover;" src="{{ url('uploads/'.$comment->user->avatar_filename) }}" alt="Avatar image">
                            @if($comment->user_id == Auth::id())
                                <img width="32" height="32" class="img-btn float-right ml-md-2 mb-2" id="delete-comment-btn" comment-id="{{$comment->id}}" src="{{ url('uploads/closeCross.png') }}" alt="Delete comment btn">
                            @endif
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
<!------------ COMMENT SECTION ENDS -------------->
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
    .img-btn:hover{
        opacity: 60%;   
    }
    .img-btn:active{
        background-color: red;
        opacity: 70%;   
    }

</style>
@endsection