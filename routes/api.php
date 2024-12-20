<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\StoreController;


// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::post('register',[UserController::class,'register']);
Route::post('login',[UserController::class,'login']);
Route::get('logout',[UserController::class,'logout']);

Route::group(["middleware"=>["auth:api"]],
    function(){
        Route::post('testjwt',[UserController::class,'testjwt']);
    });

// store
Route::group(["middleware"=>["auth:api"]],
    function(){
        Route::get('store',[StoreController::class,'index']);
        Route::post('store/add',[StoreController::class,'add']);
        Route::get('store/edit/{id}',[StoreController::class,'edit']);
        Route::post('store/update/{id}',[StoreController::class,'update']);
        Route::delete('store/delete/{id}',[StoreController::class,'delete']);
    });