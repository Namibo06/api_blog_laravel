<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Like;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    public function like(Request $req){
        //se apertar em send etiver sem seu proprio like,deixa o like,se tiver seu like,dar  deslike
        $like = Like::where('post_id',$req->post_id)
        ->where('user_id',Auth::user()->id)->get();
        //check if it returns 0 then this post is not liked and should bbe liked else unliked
        if(count($like)>0){
            //bcz wo cant have likes more than one
            $like[0]->delete();
            return response()->json([
                'success'=>true,
                'message'=>'unliked'
            ]);
        }
        $like = new Like;
        $like->user_id=Auth::user()->id;
        $like->post_id=$req->post_id;
        $like->save();

        return response()->json([
            'success'=>true,
            'message'=>'liked'
        ]);
    }
}
