<?php

use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

//route protegée; il faut d'abord etre connecté pour avoir accès a ces routes
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) { //user profile
        return $request->user();
    });

    //post
    Route::get("posts", [PostController::class, "index"]);
    Route::post("posts/create", [PostController::class, "store"]);
    Route::put("posts/edit/{post}", [PostController::class, "update"]);
    Route::delete("posts/{post}", [PostController::class, "delete"]);
});

//user
Route::post("user/register", [UserController::class, "register"]);
Route::post("user/login", [UserController::class, "login"]);
