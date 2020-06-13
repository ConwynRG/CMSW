<?php

namespace App\Http\Controllers;

use App\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function addComment(Request $request){
        $comment = new Comment();
        $comment->post_id = $request['post_id'];
        $comment->user_id = $request['user_id'];
        $comment->comment_text = $request['comment_text'];
        $comment->save();
        
        return response()->json(['comment_id'=>$comment->id,'post_id'=>$comment->post_id,
            'user_id'=>$comment->user_id,'user_avatar_filename'=>$comment->user->avatar_filename,
            'username'=>$comment->user->name,'comment_text'=>$comment->comment_text, 'date'=>$comment->created_at->format('M d, Y')], 200);
    }
    
    public function deleteComment(Request $request){
        $commentId = $request['comment_id'];
        $comment = Comment::find($commentId);
        if($comment->user_id == Auth::id() || Auth::user()->isAdmin){
            $comment->delete();
            return response()->json(['comment_id'=>$commentId, 'isDeleted'=>true], 200);
        }else{
            return response()->json(['comment_id'=>$commentId, 'isDeleted'=>false], 200);
        }
    }
}
