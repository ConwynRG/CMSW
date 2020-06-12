<?php

namespace App\Http\Controllers;

use App\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function addComment(Request $request){
        $comment = new Comment();
        $comment->post_id = $request['post_id'];
        $comment->user_id = $request['user_id'];
        $comment->comment_text = $request['comment_text'];
        $comment->save();
        
        return response()->json(['comment_id'=>$comment->id,'post_id'=>$comment->post_id,
            'user_id'=>$comment->user_id,'comment_text'=>$comment->comment_text, 'date'=>$comment->created_at->format('M d, Y')], 200);
    }
}
