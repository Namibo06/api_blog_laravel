<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function comments(Request $req){
        $comments = Comment::where('post_id',$req->id)->get();
        //show user of each comment
        foreach($comments as $comment){
            $comment->user;
            return response()->json([
                'success'=>true,
                'comments'=>$comments
            ]);
        }
    }

    public function create(Request $req){
        $comment =  new Comment;
        $comment->user_id = Auth::user()->id;
        $comment->post_id = $req->id;
        $comment->comment = $req->comment;
        $comment->save();

        return response()->json([
            'success'=>true,
            'message'=>'comment added'
        ]);
    }

    public function update(Request $req){
        $comment= Comment::find($req->id);
        //check if user is editing his own comment
        if($comment->user_id != Auth::user()->id){
            return response()->json([
                'success'=>false,
                'message'=>'unauthorize access'
            ]);
        }
        $comment->comment = $req->comment;
        $comment->update();

        return response()->json([
            'success'=>true,
            'message'=>'comment edited'
        ]);
    }

    public function delete(Request $req){
        $comment= Comment::find($req->id);
        //check if user is editing his own comment
        if($comment->user_id != Auth::user()->id){
            return response()->json([
                'success'=>false,
                'message'=>'unauthorize access'
            ]);
        }
        $comment->comment = $req->comment;
        $comment->delete();

        return response()->json([
            'success'=>true,
            'message'=>'comment deleted'
        ]);
    }
}
