<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\LikeController;
use App\Http\Controllers\Api\PostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

//user
Route::post('login',[AuthController::class,'login']);
Route::get('logout',[AuthController::class,'logout']);
Route::post('register',[AuthController::class,'register']);
Route::post('save_user_info',[AuthController::class,'save_user_info']);


//posts
Route::post('posts/create',[PostController::class,'create'])->middleware('jwtAuth');
Route::post('posts/delete',[PostController::class,'delete'])->middleware('jwtAuth');
Route::post('posts/update',[PostController::class,'update'])->middleware('jwtAuth');
Route::get('posts',[PostController::class,'posts'])->middleware('jwtAuth');


//comment
Route::post('comments/create',[CommentController::class,'create'])->middleware('jwtAuth');
Route::post('comments/delete',[CommentController::class,'delete'])->middleware('jwtAuth');
Route::post('comments/update',[CommentController::class,'update'])->middleware('jwtAuth');
Route::get('comments',[CommentController::class,'comments'])->middleware('jwtAuth');

//like
Route::post('like',[LikeController::class,'like'])->middleware('jwtAuth');
