<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function posts(){
        $posts = Post::orderBy('id','desc')->get();
        foreach($posts as $post){
            //get user of post
            $post->user;
            //comment count
            $post['commentsCount'] = count($post->comments);
            //like count
            $post['likesCount'] = count($post->likes);
            //check if user liked  own post
            $post['selfLike'] = false;
            foreach($post->likes as $like){
                if($like->user_id == Auth::user()->id){
                    $post['selfLike'] = true;
                }
            }
        }

        return response()->json([
            'success'=>true,
            'posts'=>$posts
        ]);
    }

    public function create(Request $req){
        $post = new Post;
        $post->user_id = Auth::user()->id;
        $post->desc=$req->desc;

        //check if post has photo
        if($req->photo != ''){
            //choose a unique name for photo
            $photo = time().'jpg';
            //need to link storage folder to public
            file_put_contents('storage/posts/'.$photo,base64_decode($req->photo));
            $post->photo = $photo;
        }

        $post->save();
        $post->user;
        return response()->json([
            'success'=>true,
            'message'=>'posted',
            'post'=>$post
        ]);
    }

    public function update(Request $req){
        $post = Post::find($req->id);
        //check if user iis editing his own post
        if(Auth::user()->id != $post->user_id){
            return response()->json([
                'success'=>false,
                'message'=>'unauthorized access'
            ]);
        }
        $post->desc = $req->desc;
        $post->update();
        return response()->json([
            'success'=>true,
            'message'=>'post edited',
        ]);
    }

    public function delete(Request $req){
        $post = Post::find($req->id);
        //check if user iis editing his own post
        if(Auth::user()->id != $post->user_id){
            return response()->json([
                'success'=>false,
                'message'=>'unauthorized access'
            ]);
        }
        //check if post has photo to delete
        if($post->photo != ''){
            Storage::delete('public/posts/'.$post->photo);
        }
        $post->delete();
        return response()->json([
            'success'=>true,
            'message'=>'post deleted',
        ]);
    }
}
