<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::group(["prefix" => "/v1"], function (){
    Route::group(["prefix" => "/user"], function (){
        Route::post("login",UserController::class.'@login');
        Route::post("logout",UserController::class.'@logout');
        Route::post("register",UserController::class.'@register');
    });

    Route::post("post",function (){

    });

    Route::get("post/{post_id}",function ($post_id){
        return $post_id;
    });

    Route::get("post/public",function (){

    });
});
