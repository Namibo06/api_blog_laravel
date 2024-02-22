<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function register(Request $req){
        $encryptedPass=Hash::make($req->password);

        $user = new User;

        try{
            $user->email = $req->email;
            $user->password =$encryptedPass;
            $user->save();
            return $this->login($req);
        }catch(Exception $e){
            return response()->json([
                'success'=>false,
                'message'=>''.$e
            ]);
        }
    }

    public function login(Request $req){
        $creds = $req->only(['email','password']);

        if(!$token=auth()->attempt($creds)){
            return response()->json([
                'success'=>false,
                'message'=>'invalid credentials'
            ]);
        }
        return response()->json([
            'success'=>true,
            'token'=>$token,
            'user'=>Auth::user()
        ]);
    }

    public function logout(Request $req){
        try {
            JWTAuth::invalidate(JWTAuth::parseToken($req->token));
            return response()->json([
                'success'=>true,
                'message'=>'logout success'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success'=>false,
                'message'=>''.$e
            ]);
        }
    }

    //salva name,last name e foto
    public function save_user_info(Request $req){
        $user = User::find(Auth::user()->id);
        $user->name = $req->name;
        $user->lastName = $req->lastName;
        $photo='';
        //verifica se tem foto
        if($req->photo!=''){
            //evita duplicação de nome da foto
            $photo=time().'.jpg';
            //faz decode de string da foto e salva no storage/profiles
            file_put_contents('storage/profiles/'.$photo,base64_decode($req->photo));
            $user->photo=$photo;
        }
        $user->update();

        return response()->json([
            'success'=>true,
            'photo'=>$photo
        ]);
    }
}
