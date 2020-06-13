<?php

namespace App\Http\Controllers;

use App\Post;
use App\PostReview;
use Illuminate\Http\Request;

class PostReviewController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }
    
    public function addPostReview(Request $request){
        $post = Post::find($request['post_id']);
        
        if(PostReview::where('user_id',$request['user_id'])->where('post_id',$request['post_id'])->count() == 0){
            $post->rating = $post->rating + intval($request['review_value']);
            $post->save();
            
            $review = new PostReview();
            $review->user_id = $request['user_id'];
            $review->post_id = $request['post_id'];
            $review->review = $request['review_value'];
            $review->save();
            return response()->json(['review_value'=>$review->review,'post_rating'=>$post->rating],200);
        }else{
            $review = PostReview::where('user_id',$request['user_id'])->where('post_id',$request['post_id'])->first();
            if($review->review != $request['review_value']){
                $post->rating = $post->rating + (2*intval($request['review_value']));
                $review->review = $request['review_value'];
                $review->save();
                $post->save();
                return response()->json(['review_value'=>$review->review,'post_rating'=>$post->rating],200);
            }else{
                return response()->json(['error' => 'Trying to set the same value'], 403);
            }
        }
                
    }
    
    public function nullifyPostReview(Request $request){
        $post = Post::find($request['post_id']);
        
        if(PostReview::where('user_id',$request['user_id'])->where('post_id',$request['post_id'])->count() >= 1){
            $post->rating = $post->rating - intval($request['review_value']);
            $post->save();
            $review = PostReview::where('user_id',$request['user_id'])->where('post_id',$request['post_id'])->first();
            $review->delete();
            return response()->json(['review_value'=>$request['review_value'],'post_rating'=>$post->rating],200);
        }
        else{
            return response()->json(['error' => 'Removing not existing record'], 403);
        }
    }
}
